<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Prokl\GuzzleBundle\DataCollector\GuzzleCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class GuzzleJsoner
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 16.08.2021
 */
class GuzzleDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/guzzle/list.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, GuzzleCollector::class);
    }
}
