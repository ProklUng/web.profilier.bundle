<?php

namespace Prokl\WebProfilierBundle\Events;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Prokl\WebProfilierBundle\Contract\ProfilerGuardInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class BitrixAfterHandleRequestListener
 *
 * @package Prokl\WebProfilierBundle\Events
 * @since 16.08.2021
 */
class BitrixAfterHandleRequestListener
{
    /**
     * @var ProfilerGuardInterface $guard Guard
     */
    private $guard;

    /**
     * @var array $ignoringUrls Игнорируемые URL.
     */
    private $ignoringUrls;

    /**
     * @param ProfilerGuardInterface $guard        Guard.
     * @param array                  $ignoringUrls Игнорируемые URL.
     */
    public function __construct(
        ProfilerGuardInterface $guard,
        array $ignoringUrls
    ) {
        $this->guard = $guard;
        $this->ignoringUrls = $ignoringUrls;
    }

    /**
     * @param Event $event Событие.
     *
     * @return void
     * @throws ArgumentNullException Битриксовые ошибки.
     */
    public function handle(Event $event)
    {
        if (!$this->guard->isGranted()) {
            return;
        }

        $response = $event->getResponse();
        $context = Application::getInstance()->getContext();

        $url = $context->getRequest()->getRequestUri();
        $bitrixResponse = $context->getResponse();

        if (!$this->support($url)) {
            return;
        }

        if ($response->headers->has('X-Debug-Token')) {
            $bitrixResponse->addHeader('X-Debug-Token', $response->headers->get('X-Debug-Token'));
        }

        if ($prevToken = $response->headers->get('X-Previous-Debug-Token')) {
            $bitrixResponse->addHeader('X-Previous-Debug-Token', $prevToken);
        }

        if ($response->headers->has('X-Debug-Token-Link')) {
            $bitrixResponse->addHeader('X-Debug-Token-Link', $response->headers->get('X-Debug-Token-Link'));
        }
    }

    /**
     * Не в списке ли игнорируемых.
     *
     * @param string|null $url URL.
     *
     * @return bool
     */
    private function support(?string $url) : bool
    {
        if (!$url) {
            return false;
        }

        foreach ($this->ignoringUrls as $ignoringUrl) {
            if (stripos($url, $ignoringUrl) !== false) {
                return false;
            }
        }

        return true;
    }
}