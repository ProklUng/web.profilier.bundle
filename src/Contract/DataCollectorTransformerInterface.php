<?php

namespace Prokl\WebProfilierBundle\Contract;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * Interface DataCollectorTransformerInterface
 * @package Prokl\WebProfilierBundle\Contract
 *
 * @since 16.08.2021
 */
interface DataCollectorTransformerInterface
{
    /**
     * Заполучить данные из коллектора.
     *
     * @param DataCollectorInterface $dataCollector Data collector.
     *
     * @return array
     */
    public function transform(DataCollectorInterface $dataCollector) : array;

    /**
     * Твиговский шаблон.
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * Проверка - подходит ли переданный коллектор этому обработчику.
     *
     * @param DataCollector $dataCollector Data collector.
     *
     * @return bool
     */
    public static function support(DataCollector $dataCollector) : bool;
}