<?php

namespace Prokl\WebProfilierBundle\Transformers;

use EonX\EasyWebhook\Bridge\Symfony\DataCollector\WebhookDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class WebHookDataCollectorTransformer
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 18.08.2021
 */
class WebHookDataCollectorTransformer extends BaseDataCollectorTransformer
{
    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/webhook_collector.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, WebhookDataCollector::class);
    }
}
