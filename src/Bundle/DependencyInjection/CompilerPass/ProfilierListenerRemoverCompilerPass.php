<?php

namespace Prokl\WebProfilierBundle\Bundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TransformersRemovingCompilerPass
 * @package Prokl\WebProfilierBundle\Bundle\DependencyInjection\CompilerPass
 *
 * @since 21.08.2021
 */
class ProfilierListenerRemoverCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('profiler_listener')) {
            return;
        }

        $container->removeDefinition('profiler_listener');
    }
}