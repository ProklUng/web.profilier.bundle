<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Snc\RedisBundle\DataCollector\RedisDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class RedisDataCollectorTransformer
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 18.08.2021
 */
class RedisDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/redis.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, RedisDataCollector::class);
    }
}
