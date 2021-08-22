<?php

namespace Prokl\WebProfilierBundle\Events;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\EventListener\ProfilerListener;

/**
 * Class ProfileListenerDecorator
 *
 * @since 21.08.2021
 */
class ProfileListenerDecorator implements EventSubscriberInterface
{
    /**
     * @var ProfileListener $profileListener
     */
    private $profileListener;

    /**
     * @var array $excludedUrl Игнорируемые URL.
     */
    private $excludedUrl;

    /**
     * @param ProfilerListener $profileListener
     */
    public function __construct(
        ProfilerListener $profileListener,
        array $excludedUrl
    ) {
        $this->profileListener = $profileListener;
        $this->excludedUrl = $excludedUrl;
    }

    /**
     * Handles the onKernelResponse event.
     *
     * @param ResponseEvent $event
     *
     * @return void
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $url = $request->getRequestUri();

        foreach ($this->excludedUrl as $item) {
            if (stripos($url, $item) !== false) {
                return;
            }
        }

        return $this->profileListener->onKernelResponse($event);
    }

    public function onKernelException(ExceptionEvent $event)
    {
        return $this->profileListener->onKernelException($event);
    }

    /**
     * @param TerminateEvent $event
     *
     * @return void
     */
    public function onKernelTerminate(TerminateEvent $event)
    {
        $request = $event->getRequest();
        $url = $request->getRequestUri();
        foreach ($this->excludedUrl as $item) {
            if (stripos($url, $item) !== false) {
                return;
            }
        }

        return $this->profileListener->onKernelTerminate($event);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -100],
            KernelEvents::EXCEPTION => ['onKernelException', 0],
            KernelEvents::TERMINATE => ['onKernelTerminate', -1024],
        ];
    }
}