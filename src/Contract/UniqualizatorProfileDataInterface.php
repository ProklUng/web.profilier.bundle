<?php

namespace Prokl\WebProfilierBundle\Contract;

/**
 * Interface UniqualizatorProfileDataInterface
 * @package Prokl\WebProfilierBundle\Contract
 *
 * @since 20.08.2021
 */
interface UniqualizatorProfileDataInterface
{
    /**
     * Уникализировать тем или иным способом путь к файлу.
     *
     * @param string $basePath Базовый путь.
     *
     * @return string
     */
    public function unique(string $basePath) : string;

    /**
     * Получить первую часть имени файла с кэшом (название + уникальное для юзера ID).
     *
     * @param string $basePath Базовый путь.
     *
     * @return string
     */
    public function baseFilename(string $basePath) : string;
}