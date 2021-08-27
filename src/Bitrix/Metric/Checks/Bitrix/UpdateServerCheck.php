<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;
use CSiteCheckerTest;

/**
 * Класс для проверки доступа к серверу обновлений
 * Class UpdateServerCheck
 * @package Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix
 */
class UpdateServerCheck extends Check
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        if ($_ENV['DEBUG']) {
            $this->logError('Имеет смысл только на production окружении');
            return false;
        }

        $result = true;
        if (!(new CSiteCheckerTest)->check_update()) {
            $result = false;
            $this->logError('Нет соединения с сервером обновлений');
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'Проверка доступа к серверу обновлений...';
    }
}
