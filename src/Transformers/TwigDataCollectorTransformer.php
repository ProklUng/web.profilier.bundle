<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Symfony\Bridge\Twig\DataCollector\TwigDataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Prokl\WebProfilierBundle\Contract\DataCollectorTransformerInterface;

/**
 * Class TwigJsoner
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 17.08.2021
 */
class TwigDataCollectorTransformer implements DataCollectorTransformerInterface
{
    /**
     * @inheritDoc
     * @param TwigDataCollector $dataCollector Data collector.
     */
    public function transform($dataCollector) : array
    {
        return [
            'collector' => $dataCollector,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTemplate() : string
    {
        return '/collectors/twig.html.twig';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return is_a($dataCollector, TwigDataCollector::class);
    }
}