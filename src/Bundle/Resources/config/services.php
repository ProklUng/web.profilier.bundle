<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Prokl\WebProfilierBundle\WebDebugToolbarListener;
use Symfony\Bridge\Twig\DataCollector\TwigDataCollector;
use Symfony\Bridge\Twig\Extension\CodeExtension;
use Symfony\Bridge\Twig\Extension\ProfilerExtension;
use Symfony\Bridge\Twig\Extension\StopwatchExtension;
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

        ->set('twig.extension.profiler', ProfilerExtension::class)
        ->public()
        ->args([service('twig.profile'), service('debug.stopwatch')->ignoreOnInvalid()])

        ->set('twig.extension.code', CodeExtension::class)
        ->public()
        ->args([service('debug.file_link_formatter')->ignoreOnInvalid(), param('kernel.project_dir'), param('kernel.charset')])
        ->tag('twig.extension')

        ->set('twig.extension.profiler', ProfilerExtension::class)
        ->public()
        ->args([service('twig.profile'), service('debug.stopwatch')->ignoreOnInvalid()])

        ->set('twig.extension.debug.stopwatch', StopwatchExtension::class)
        ->public()
        ->args([service('debug.stopwatch')->ignoreOnInvalid(), param('kernel.debug')])
    ;
};
