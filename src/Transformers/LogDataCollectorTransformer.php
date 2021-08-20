<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LoggerDataCollector;

/**
 * Class LogDataCollectorTransformer
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 17.08.2021
 */
class LogDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/logger.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, LoggerDataCollector::class);
    }
}
