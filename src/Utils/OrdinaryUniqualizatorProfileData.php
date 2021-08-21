<?php

namespace Prokl\WebProfilierBundle\Utils;

use Prokl\WebProfilierBundle\Contract\UniqualizatorProfileDataInterface;

/**
 * Class OrdinaryUniqualizatorProfileData
 * @package Prokl\WebProfilierBundle\Utils
 *
 * @since 20.08.2021
 */
class OrdinaryUniqualizatorProfileData implements UniqualizatorProfileDataInterface
{
    /**
     * @inheritDoc
     */
    public function unique(string $basePath) : string
    {
        return $basePath;
    }

    /**
     * @inheritDoc
     */
    public function baseFilename(string $basePath) : string
    {
        return 'package';
    }
}