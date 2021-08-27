<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric;

use Prokl\WebProfilierBundle\Transformers\BaseDataCollectorTransformer;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class MetricsDataCollectorTransformer
 */
class MetricsDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/metrics.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, MetrixDataCollector::class);
    }
}