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
}