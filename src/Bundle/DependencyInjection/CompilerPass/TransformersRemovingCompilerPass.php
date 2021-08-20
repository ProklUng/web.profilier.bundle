<?php

namespace Prokl\WebProfilierBundle\Bundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TransformersRemovingCompilerPass
 * @package Prokl\WebProfilierBundle\Bundle\DependencyInjection\CompilerPass
 *
 * @since 20.08.2021
 */
class TransformersRemovingCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('web_profilier.disabled_profilers')) {
            return;
        }

        $disabledProfilers = $container->getParameter('web_profilier.disabled_profilers');

        $taggedServices = $container->findTaggedServiceIds('web_profiler.transformer');

        foreach ($taggedServices as $id => $service) {
            $def = $container->getDefinition($id);
            $tagInfo = $def->getTag('web_profiler.transformer');
            $key = (string)$tagInfo[0]['key'];
            if (!$key) {
                continue;
            }

            if (array_key_exists($key, $disabledProfilers) && $disabledProfilers[$key] === false) {
                $container->removeDefinition($id);
            }
        }
    }
}