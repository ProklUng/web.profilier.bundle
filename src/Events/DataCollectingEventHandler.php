<?php

namespace Prokl\WebProfilierBundle\Events;

use Bitrix\Main\Application;
use Exception;
use Prokl\WebProfilierBundle\Contract\DataFileHandlerInterface;
use Prokl\WebProfilierBundle\Contract\ProfilerGuardInterface;
use Prokl\WebProfilierBundle\Extractor\ProfileExtractor;
use Prokl\WebProfilierBundle\Utils\ExternalDataCollectorsBag;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * Class DataCollectingEventHandler
 * @package Prokl\WebProfilierBundle\Events
 */
class DataCollectingEventHandler
{
    /**
     * @var Profiler Profiler.
     */
    private $profiler;

    /**
     * @var DataFileHandlerInterface $dataFileHandler
     */
    private $dataFileHandler;

    /**
     * @var ProfilerGuardInterface $guard
     */
    private $guard;

    /**
     * @var ProfileExtractor $profileExtractor
     */
    private $profileExtractor;

    /**
     * DataCollectingEventHandler constructor.
     *
     * @param Profiler                    $profiler                Profiler.
     * @param ProfilerGuardInterface      $guard                   Guard.
     * @param DataFileHandlerInterface    $dataFileHandler         Обработчик файлов профайлера.
     */
    public function __construct(
        Profiler $profiler,
        ProfilerGuardInterface $guard,
        ProfileExtractor $profileExtractor,
        DataFileHandlerInterface $dataFileHandler
    ) {
        $this->profiler = $profiler;
        $this->guard = $guard;
        $this->dataFileHandler = $dataFileHandler;
        $this->profileExtractor = $profileExtractor;
    }

    /**
     * Движуха.
     *
     * @param Response|null $sfResponse Symfony Response.
     * @param Request|null  $sfRequest  Symfony Request.
     *
     * @return void
     * @throws Exception
     */
    public function handle(Response $sfResponse = null, Request $sfRequest = null): void
    {
        if (!$this->guard->isGranted()) {
            $this->profiler->disable();
            return;
        }

        if (!$GLOBALS['USER']->IsAdmin()) {
            $this->profiler->disable();
            return;
        }

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        $sfRequest = $sfRequest ?? Request::createFromGlobals();

        $isSymfonyRoute = false;
        if ($sfRequest->headers && $sfRequest->headers->has('X-Symfony-route')) {
            $isSymfonyRoute = true;
        }

        $url = $request->getRequestUri();
        $response = $context->getResponse();
        $headers = $response->getHeaders()->toArray();

        if (!empty($headers['x-debug-token']['values'][0])) {
            $symfonyResponse = new Response(
                $isSymfonyRoute ? $sfResponse->getContent() : $response->getContent(),
                $isSymfonyRoute ? $sfResponse->getStatusCode() : ($response->getStatus() ?? 200),
                $isSymfonyRoute ? $sfResponse->headers->all() : $response->getHeaders()->toArray()
            );

            // Миграция кук из request в response
            if ($isSymfonyRoute) {
                $responseCookies = [];
                foreach ($sfRequest->cookies->all() as $cookie) {
                    $responseCookies[$cookie] = $cookie;
                }

                foreach ($responseCookies as $cookieName => $responseCookie) {
                    if (!$cookieName) {
                        continue;
                    }
                    $symfonyResponse->headers->setCookie(new Cookie($cookieName, $responseCookie));
                }
            }

            $profile = $this->profiler->collect($sfRequest, $symfonyResponse);

            if (!$profile) {
                return;
            }

            // Суть манипуляций: зацепить внешние дата-коллекторы, полученные через событие
            // и пустить их в дело.
            $externalCollectorsBag = new ExternalDataCollectorsBag();
            $externalCollectors = $externalCollectorsBag->all();

            foreach ($externalCollectors as $externalCollector) {
                $profile->addCollector($externalCollector);
            }

            $this->profiler->saveProfile($profile);

            $json = $this->profileExtractor->extract($profile);

            $result[$url] = $json;
            $this->dataFileHandler->write($result);
        }
    }
}