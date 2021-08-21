<?php

namespace Prokl\WebProfilierBundle;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class WebDebugToolbarListener
 * Проставляет заголовки X-Debug-Token симфоническим роутам.
 */
final class WebDebugToolbarListener implements EventSubscriberInterface
{
    public const DISABLED = 1;
    public const ENABLED = 2;

    /**
     * @var UrlGeneratorInterface|null $urlGenerator
     */
    private $urlGenerator;

    /**
     * @var integer $mode
     */
    private $mode;

    public function __construct(
        int $mode = self::ENABLED,
        UrlGeneratorInterface $urlGenerator = null
    ) {
        $this->urlGenerator = $urlGenerator;
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

        if ($response->headers->has('X-Debug-Token') && null !== $this->urlGenerator) {
            try {
                $response->headers->set(
                    'X-Debug-Token-Link',
                    $this->urlGenerator->generate(
                        '_profiler',
                        ['token' => $response->headers->get('X-Debug-Token')],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
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
