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
     * @var integer $userId ID битриксового пользователя.
     */
    private $userId;

    /**
     * @inheritDoc
     */
    public function unique(string $basePath) : string
    {
        $this->userId = (int)$GLOBALS['USER']->GetID();

        return $basePath . '/package.' . $this->userId . '.' . bitrix_sessid() . '.json';
    }

    /**
     * @inheritDoc
     */
    public function baseFilename(string $basePath) : string
    {
        $this->userId = (int)$GLOBALS['USER']->GetID();

        return 'package.' . $this->userId;
    }
}