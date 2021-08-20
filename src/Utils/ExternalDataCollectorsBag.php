<?php

namespace Prokl\WebProfilierBundle\Utils;

use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * Class ExternalDataCollectorsBag
 *
 * @since 20.08.2021
 */
class ExternalDataCollectorsBag
{
    /**
     * @var DataCollectorInterface[] $dataCollectors Внешние data collectors.
     */
    private static $dataCollectors = [];

    /**
     * @return DataCollectorInterface[]
     */
    public static function getDataCollectors(): array
    {
        return static::$dataCollectors;
    }

    /**
     * Добавить.
     *
     * @param string                 $name          ID.
     * @param DataCollectorInterface $dataCollector Дата коллектор.
     *
     * @return void
     */
    public function add(string $name, DataCollectorInterface $dataCollector)
    {
        // Избежать повторных непредсказуемых добавлений.
        if (array_key_exists($name, static::$dataCollectors)) {
            return;
        }

        static::$dataCollectors[$name] = $dataCollector;
    }

    /**
     * Все внешние дата коллекторы.
     *
     * @return DataCollectorInterface[]
     */
    public function all() : array
    {
        return static::$dataCollectors;
    }
}