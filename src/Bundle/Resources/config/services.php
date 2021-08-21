<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Prokl\WebProfilierBundle\WebDebugToolbarListener;

return static function (ContainerConfigurator $container) {
    $container->services()
         ->set('web_profiler.debug_toolbar', WebDebugToolbarListener::class)
            ->public()
            ->args([
                true
            ])
            ->tag('kernel.event_subscriber')
    ;
};
