<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;

/**
 * Класс для проверки выполнения агентов на cron
 * Class AgentsUsingCronCheck
 * @package Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix
 */
class AgentsUsingCronCheck extends Check
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $result = true;
        if (!BX_CRONTAB_SUPPORT) {
            $this->logError('Агенты не выполняются на cron');
            $result = false;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'Проверка выполнения агентов на cron...';
    }
}
