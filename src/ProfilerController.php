<?php

namespace Prokl\WebProfilierBundle;

use Prokl\WebProfilierBundle\Extractor\DataCollectorTransformerBag;
use Prokl\WebProfilierBundle\Extractor\ProfileExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @internal
 */
class ProfilerController
{
    private $generator;
    private $profiler;

    /**
     * @var DataCollectorTransformerBag $collectorTransformerBag
     */
    private $collectorTransformerBag;

    public function __construct(
        UrlGeneratorInterface $generator,
        DataCollectorTransformerBag $collectorTransformerBag,
        Profiler $profiler = null
    ) {
        $this->generator = $generator;
        $this->profiler = $profiler;
        $this->collectorTransformerBag = $collectorTransformerBag;
    }

    /**
     * Renders the Web Debug Toolbar.
     *
     * @return Response A Response instance
     */
    public function toolbarAction(Request $request, string $token = null)
    {
        if (null === $this->profiler) {
            throw new NotFoundHttpException('The profiler must be enabled.');
        }

        if ($request->hasSession() && ($session = $request->getSession())->isStarted() && $session->getFlashBag() instanceof AutoExpireFlashBag) {
            // keep current flashes for one more request if using AutoExpireFlashBag
            $session->getFlashBag()->setAll($session->getFlashBag()->peekAll());
        }

        if ('empty' === $token || null === $token) {
            return new Response('', 200, ['Content-Type' => 'text/html']);
        }

        $this->profiler->disable();

        if (!$profile = $this->profiler->loadProfile($token)) {
            return new Response('', 404, ['Content-Type' => 'text/html']);
        }

        $url = null;
        try {
            $url = $this->generator->generate('_profiler', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
        } catch (\Exception $e) {
            // the profiler is not enabled
            return new Response('');
        }

        $extractor = new ProfileExtractor($this->collectorTransformerBag);
        $json = $extractor->extract($profile);

        return new Response(json_encode($json));
    }
}
