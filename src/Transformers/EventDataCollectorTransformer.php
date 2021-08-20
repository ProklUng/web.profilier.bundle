<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\EventDataCollector;

/**
 * Class EventJsoner
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 17.08.2021
 */
class EventDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/events.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, EventDataCollector::class);
    }
}
