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
        $userId = (int)$GLOBALS['USER']->GetID();

        return $basePath .'/package.' . $userId . '.' . bitrix_sessid() . '.json';
    }
}