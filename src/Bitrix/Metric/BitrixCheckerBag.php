<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\AgentsUsingCronCheck;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\ApacheModules;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\CacheDirPermissionsCheck;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\DataBaseCheck;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\DataBaseConfigsMatchForBothCores;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\EmailsCanBeSend;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\Mail;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\PhpSettings;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\RequiredPhpModules;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\ServiceScriptsAreRemoved;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix\UpdateServerCheck;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom\BasicAuthIsTurnedOn;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom\BitrixDebugIsTurnedOff;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom\BitrixDebugIsTurnedOn;
use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom\XDebugIsNotLoaded;

/**
 * Class BitrixCheckerBag
 *
 * @since 27.08.2021
 */
class BitrixCheckerBag
{
    /**
     * @var array $checkers
     */
    private $checkers = [];

    /**
     * @return array
     */
    public function getCheckers(): array
    {
        return $this->checkers;
    }

    public function __construct()
    {
        $this->checkers = [
            new AgentsUsingCronCheck(),
            new ApacheModules(),
            new CacheDirPermissionsCheck(),
            new UpdateServerCheck(),
            new DataBaseCheck(),
            new DataBaseConfigsMatchForBothCores(),
            new EmailsCanBeSend(),
            new Mail(),
            new PhpSettings(),
            new RequiredPhpModules(),
            new ServiceScriptsAreRemoved(),
            // new BasicAuthIsTurnedOn(),
            new BitrixDebugIsTurnedOff(),
            new BitrixDebugIsTurnedOn(),
            new XDebugIsNotLoaded(),
        ];
    }
}