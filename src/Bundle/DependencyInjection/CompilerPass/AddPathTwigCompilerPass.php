<?php

namespace Prokl\WebProfilierBundle\Bundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AddPathTwigCompilerPass
 * @package Prokl\WebProfilierBundle\Bundle\DependencyInjection\CompilerPass
 *
 * @since 17.08.2021
 */
class AddPathTwigCompilerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        // Подмес путей к твиговским шаблонам бандла.
        $twigLoaderDef = $container->getDefinition('profilier.twig_loader');
        foreach ($container->getParameter('web_profilier.profiler_twig_templates_path') as $path) {
            $twigLoaderDef->addMethodCall('addPath', [$path]);
        }

        $definition = $container->getDefinition('profilier.twig');

        // Extensions must always be registered before everything else.
        // For instance, global variable definitions must be registered
        // afterward. If not, the globals from the extensions will never
        // be registered.
        $currentMethodCalls = $definition->getMethodCalls();
        $twigBridgeExtensionsMethodCalls = [];
        $othersExtensionsMethodCalls = [];
        foreach ($this->findAndSortTaggedServices('twig.extension', $container) as $extension) {
            $methodCall = ['addExtension', [$extension]];
            $extensionClass = $container->getDefinition((string) $extension)->getClass();

            if (\is_string($extensionClass) && str_starts_with($extensionClass, 'Symfony\Bridge\Twig\Extension')) {
                $twigBridgeExtensionsMethodCalls[] = $methodCall;
            } else {
                $othersExtensionsMethodCalls[] = $methodCall;
            }
        }

        if (!empty($twigBridgeExtensionsMethodCalls) || !empty($othersExtensionsMethodCalls)) {
            $definition->setMethodCalls(array_merge($twigBridgeExtensionsMethodCalls, $othersExtensionsMethodCalls, $currentMethodCalls));
        }
    }
}