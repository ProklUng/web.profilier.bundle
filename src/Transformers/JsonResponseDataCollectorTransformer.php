<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Prokl\WebProfilierBundle\DataCollector\JsonResponseDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class JsonResponseDataCollectorTransformer
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 19.08.2021
 */
class JsonResponseDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/json_response.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, JsonResponseDataCollector::class);
    }
}
