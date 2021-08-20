<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Prokl\WebProfilierBundle\ContentSecurityPolicyHandler;
use Prokl\WebProfilierBundle\NonceGenerator;
use Prokl\WebProfilierBundle\ProfilerController;
use Prokl\WebProfilierBundle\WebDebugToolbarListener;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('web_profiler.controller.profiler', ProfilerController::class)
        ->public()
        ->args([
            service('router')->nullOnInvalid(),
            service('transformers.bag'),
            service('profiler')->nullOnInvalid(),
        ])->tag('controller.service_arguments')
        ->public()

        ->alias(ProfilerController::class, 'web_profiler.controller.profiler')->public()

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
