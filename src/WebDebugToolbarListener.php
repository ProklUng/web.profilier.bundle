<?php

namespace Prokl\WebProfilierBundle;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class WebDebugToolbarListener
 * Проставляет заголовки X-Debug-Token симфоническим роутам.
 */
final class WebDebugToolbarListener implements EventSubscriberInterface
{
    public const DISABLED = 1;
    public const ENABLED = 2;

    /**
     * @var integer $mode
     */
    private $mode;

    public function __construct(
        int $mode = self::ENABLED
    ) {
        $this->mode = $mode;
    }

    public function isEnabled(): bool
    {
        return self::DISABLED !== $this->mode;
    }

    /**
     * @param ResponseEvent $event
     *
     * @return void
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();

        if ($response->headers->has('X-Debug-Token')) {
            try {
                $response->headers->set(
                    'X-Debug-Token-Link',
                    '/bitrix/admin/_profilier/'
                );
            } catch (\Exception $e) {
                $response->headers->set(
                    'X-Debug-Error',
                    \get_class($e).': '.preg_replace('/\s+/', ' ', $e->getMessage())
                );
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -128],
        ];
    }
}