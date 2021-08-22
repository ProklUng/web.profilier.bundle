<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Prokl\WebProfilierBundle\WebDebugToolbarListener;
use Symfony\Bridge\Twig\DataCollector\TwigDataCollector;
use Twig\Profiler\Profile;

return static function (ContainerConfigurator $container) {
    $container->services()
         ->set('web_profiler.debug_toolbar', WebDebugToolbarListener::class)
            ->public()
            ->args([
                true
            ])
            ->tag('kernel.event_subscriber')

        ->set('data_collector.twig', TwigDataCollector::class)
        ->public()
        ->args([service('twig.profile'), service('twig.instance')])
        ->tag('data_collector', ['template' => '@WebProfiler/Collector/twig.html.twig', 'id' => 'twig', 'priority' => 257])

        ->set('twig.profile', Profile::class)
    ;
};
