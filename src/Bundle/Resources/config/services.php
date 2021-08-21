<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Prokl\WebProfilierBundle\ContentSecurityPolicyHandler;
use Prokl\WebProfilierBundle\NonceGenerator;
use Prokl\WebProfilierBundle\WebDebugToolbarListener;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('web_profiler.csp.handler', ContentSecurityPolicyHandler::class)
        ->args([
            inline_service(NonceGenerator::class),
        ])

        ->set('web_profiler.debug_toolbar', WebDebugToolbarListener::class)
            ->public()
            ->args([
                false,
                true,
                service('router')->ignoreOnInvalid(),
                '',
                service('web_profiler.csp.handler'),
            ])
            ->tag('kernel.event_subscriber')
    ;
};
