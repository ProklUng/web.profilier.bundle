<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Symfony\Component\HttpKernel\DataCollector\ConfigDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class ConfigJsoner
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 17.08.2021
 */
class ConfigDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     * @param ConfigDataCollector $dataCollector Data collector.
     */
    public function transform($dataCollector) : array
    {
        return [
            'symfonyState' => $dataCollector->getSymfonyState(),
            'symfonyversion' => $dataCollector->getSymfonyVersion(),
            'symfonystate' => $dataCollector->getSymfonyState(),
            'symfonylts' => $dataCollector->isSymfonyLts(),
            'symfonyeom' => $dataCollector->getSymfonyEom(),
            'symfonyeol' => $dataCollector->getSymfonyEol(),
            'symfonyminorversion' => $dataCollector->getSymfonyMinorVersion(),
            'token' => $dataCollector->getToken(),
            'env' => $dataCollector->getEnv(),
            'debug' => $dataCollector->isDebug(),
            'phpversionextra' => $dataCollector->getPhpVersionExtra(),
            'phpversion' => $dataCollector->getPhpVersion(),
            'phparchitecture' => $dataCollector->getPhpArchitecture(),
            'phpintllocale' => $dataCollector->getPhpIntlLocale(),
            'phptimezone' => $dataCollector->getPhpTimezone(),
            'hasxdebug' => $dataCollector->hasXDebug(),
            'hasapcu' => $dataCollector->hasApcu(),
            'haszendopcache' => $dataCollector->hasZendOpcache(),
            'sapiName' => $dataCollector->getSapiName(),
            'bundles' => $dataCollector->getBundles(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/config.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, ConfigDataCollector::class);
    }
}
