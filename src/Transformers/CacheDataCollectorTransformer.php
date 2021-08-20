<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Symfony\Component\Cache\DataCollector\CacheDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class CacheDataCollectorTransformer
 * @package Prokl\WebProfilierBundle\Jsoner
 *
 * @since 17.08.2021
 */
class CacheDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/cache.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, CacheDataCollector::class);
    }
}
