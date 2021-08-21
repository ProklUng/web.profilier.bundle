<?php

namespace Prokl\WebProfilierBundle\Bundle;

use Prokl\WebProfilierBundle\Bundle\DependencyInjection\CompilerPass\TransformersRemovingCompilerPass;
use Prokl\WebProfilierBundle\Bundle\DependencyInjection\CustomWebProfilerBundleExtension;
use Prokl\WebProfilierBundle\Bundle\DependencyInjection\CompilerPass\AddPathTwigCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class CustomWebProfilerBundle
 * @package Prokl\WebProfilierBundle\Bundle
 *
 * @since 17.08.2021
 */
final class CustomWebProfilerBundle extends Bundle
{
   /**
   * @inheritDoc
   */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new CustomWebProfilerBundleExtension();
        }

        return $this->extension;
    }

    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddPathTwigCompilerPass());
        $container->addCompilerPass(new TransformersRemovingCompilerPass());
    }
}
