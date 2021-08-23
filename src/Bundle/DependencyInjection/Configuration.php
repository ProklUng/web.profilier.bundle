<?php
declare(strict_types=1);

namespace Prokl\WebProfilierBundle\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Prokl\WebProfilierBundle\Bundle\DependencyInjection
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('custom-web-profiler');

        $treeBuilder->getRootNode()
            ->children()
            ->booleanNode('enabled')->defaultValue(true)->end()
            ->scalarNode('profiler_cache_path')->defaultValue('%kernel.cache_dir%/custom_profilier')->end()
            ->scalarNode('admin_profiler_page_template')->defaultValue('./profiler/layout.html.twig')->end()
            ->arrayNode('profiler_twig_templates_path')
                ->useAttributeAsKey('name')
                ->prototype('scalar')->end()
                // @phpstan-ignore-next-line
                ->defaultValue([])
            ->end()
            ->arrayNode('ignoring_url')
                ->useAttributeAsKey('name')
                ->prototype('scalar')->end()
                // @phpstan-ignore-next-line
                ->defaultValue(['/bitrix/admin', 'spread.php',  'public_session.php', '/_profiler', '/_profilier'])
            ->end()
            ->arrayNode('profilers')
                ->useAttributeAsKey('name')
                ->prototype('scalar')->end()
                // @phpstan-ignore-next-line
                ->defaultValue([])
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
