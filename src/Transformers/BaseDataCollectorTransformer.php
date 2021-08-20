<?php

namespace Prokl\WebProfilierBundle\Transformers;

use Prokl\WebProfilierBundle\Contract\DataCollectorTransformerInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class BaseDataCollectorTransformer
 *
 * @package Prokl\WebProfilierBundle\Transformers
 *
 * @since 20.08.2021
 */
class BaseDataCollectorTransformer implements DataCollectorTransformerInterface
{
    /**
     * @inheritDoc
     * @param DataCollector $dataCollector Data collector.
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
        return '';
    }

    /**
     * @inheritDoc
     */
    public static function support(DataCollector $dataCollector) : bool
    {
        return false;
    }
}