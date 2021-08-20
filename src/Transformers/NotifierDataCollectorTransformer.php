<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\Notifier\DataCollector\NotificationDataCollector;

/**
 * Class NotifierJsoner
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 17.08.2021
 */
class NotifierDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/notifier.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, NotificationDataCollector::class);
    }
}
