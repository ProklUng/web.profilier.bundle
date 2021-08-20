<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\Messenger\DataCollector\MessengerDataCollector;

/**
 * Class MessengerDataCollectorTransformer
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 18.08.2021
 */
class MessengerDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/messenger.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, MessengerDataCollector::class);
    }
}
