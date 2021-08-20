<?php

namespace Prokl\WebProfilierBundle;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * WebDebugToolbarListener injects the Web Debug Toolbar.
 *
 * The onKernelResponse method must be connected to the kernel.response event.
 *
 * The WDT is only injected on well-formed HTML (with a proper </body> tag).
 * This means that the WDT is never included in sub-requests or ESI requests.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
final class WebDebugToolbarListener implements EventSubscriberInterface
{
    public const DISABLED = 1;
    public const ENABLED = 2;

    protected $twig;
    protected $urlGenerator;
    protected $interceptRedirects;
    protected $mode;
    protected $excludedAjaxPaths;
    private $cspHandler;

    public function __construct(
        bool $interceptRedirects = false,
        int $mode = self::ENABLED,
        UrlGeneratorInterface $urlGenerator = null,
        string $excludedAjaxPaths = '^/bundles|^/_wdt',
        ContentSecurityPolicyHandler $cspHandler = null
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->interceptRedirects = $interceptRedirects;
        $this->mode = $mode;
        $this->excludedAjaxPaths = $excludedAjaxPaths;
        $this->cspHandler = $cspHandler;
    }

    public function isEnabled(): bool
    {
        return self::DISABLED !== $this->mode;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

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

        if (!$event->isMasterRequest()) {
            return;
        }

        $this->cspHandler ? $this->cspHandler->updateResponseHeaders($request, $response) : [];
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
