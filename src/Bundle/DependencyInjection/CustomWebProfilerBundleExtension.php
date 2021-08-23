<?php

namespace Prokl\WebProfilierBundle\Bundle\DependencyInjection;

use Exception;
use InvalidArgumentException;
use Prokl\BitrixSymfonyRouterBundle\Services\Router\InitRouter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class CustomWebProfilerBundle
 * @package Prokl\WebProfilierBundle\Bundle
 *
 * @since 17.08.2021
 */
final class CustomWebProfilerBundleExtension extends Extension
{
    private const DIR_CONFIG = '/../Resources/config';

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('web_profilier.enabled', $config['enabled']);

        if (!$config['enabled'] || !$_SERVER['REQUEST_URI']) {
            $container->setParameter('web_profilier.enabled', false);
            return;
        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . self::DIR_CONFIG)
        );

        $loaderPhp = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__ . self::DIR_CONFIG)
        );

        $loader->load('services.yaml');
        $loader->load('twig.yaml');
        $loader->load('data_collectors.yaml');
        $loader->load('transformers.yaml');
        $loaderPhp->load('services.php');

        // Битрикс
        if (defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED === true) {
            $loader->load('bitrix.yaml');
            try {
                $this->loadRoutes(__DIR__ . self::DIR_CONFIG, 'routes_bitrix.yaml');
            } catch (InvalidArgumentException $e) {
                // Нет роутера - не страшно.
            }

            $this->installFiles($container->getParameter('kernel.project_dir'));
        }

        $container->setParameter('web_profilier.profiler_cache_path', $config['profiler_cache_path']);

        $container->setParameter('web_profilier.admin_profiler_page_template', $config['admin_profiler_page_template']);

        $config['profiler_twig_templates_path'][] = __DIR__ . '/../Resources/view'; // Дефолтные твиговские шаблоны
        $twigPaths = array_unique($config['profiler_twig_templates_path']);

        $container->setParameter('web_profilier.profiler_twig_templates_path', $twigPaths);
        $container->setParameter('web_profilier.ignoring_url', $config['ignoring_url']);
        $container->setParameter('web_profilier.disabled_profilers', $config['profilers']);
    }

    /**
     * @inheritDoc
     */
    public function getAlias() : string
    {
        return 'custom-web-profiler';
    }

    /**
     * Загрузить роуты в бандле.
     *
     * @param string $path   Путь к конфигу.
     * @param string $config Конфигурационный файл.
     *
     * @return void
     *
     * @throws InvalidArgumentException Нет класса-конфигуратора роутов.
     */
    private function loadRoutes(string $path, string $config = 'routes.yaml') : void
    {
        $routeLoader = new \Symfony\Component\Routing\Loader\YamlFileLoader(
            new FileLocator($path)
        );

        $routes = $routeLoader->load($config);

        if (class_exists(InitRouter::class)) {
            InitRouter::addRoutesBundle($routes);
            return;
        }

        throw new InvalidArgumentException('Class InitRouter not exist.');
    }

    /**
     * Копирование файлов.
     *
     * @param string $root DOCUMENT_ROOT.
     *
     * @return void
     */
    private function installFiles(string $root): void
    {
        // Без роутера.
        if (!@file_exists($root.'/bitrix/admin/_profiler.php')) {
            copy(__DIR__.'/../../Install/_profiler.php', $root.'/bitrix/admin/_profiler.php');
        }

        if (!@file_exists($root.'/bitrix/admin/_profiler_clear.php')) {
            copy(__DIR__.'/../../Install/_profiler_clear.php', $root.'/bitrix/admin/_profiler_clear.php');
        }

        if (!@file_exists($root.'/bitrix/images/symfony.png')) {
            copy(__DIR__.'/../../Install/symfony.png', $root.'/bitrix/images/symfony.png');
        }
    }
}