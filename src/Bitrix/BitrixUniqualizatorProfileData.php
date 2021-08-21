<?php

namespace Prokl\WebProfilierBundle\Bitrix;

use Prokl\WebProfilierBundle\Contract\UniqualizatorProfileDataInterface;

/**
 * Class BitrixUniqualizatorProfileData
 * @package Prokl\WebProfilierBundle\Bitrix
 *
 * @since 20.08.2021
 */
class BitrixUniqualizatorProfileData implements UniqualizatorProfileDataInterface
{
    /**
     * @inheritDoc
     */
    public function unique(string $basePath) : string
    {
        $userId = $GLOBALS['USER'];

        return $basePath .'/package.' . bitrix_sessid() . '.json';
    }
}