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
     * @param ProfilerListener $profileListener
     */
    public function __construct(ProfilerListener $profileListener)
    {
        $this->profileListener = $profileListener;
    }

    /**
     * Handles the onKernelResponse event.
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $url = $request->getRequestUri();
        if (stripos($url, 'public_session.php') !== false) {
            return;
        }

        return $this->profileListener->onKernelResponse($event);
    }

    public function onKernelException(ExceptionEvent $event)
    {
        return $this->profileListener->onKernelException($event);
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        $request = $event->getRequest();
        $url = $request->getRequestUri();
        if (stripos($url, 'public_session.php') !== false) {
            return;
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