<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;
use CSiteCheckerTest;

/**
 * Класс для тестирования отправки писем
 * Class Mail
 * @package Prokl\WebProfilierBundle\Bitrix\Metric\Custom
 */
class EmailsCanBeSend extends Check
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $siteChecker = new CSiteCheckerTest;
        if (!$siteChecker->check_mail()) {
            $this->logError('Отправка почтового сообщения не удалась');
            return false;
        }

        if (!$siteChecker->check_mail_big()) {
            $this->logError('Отправка большого почтового сообщения не удалась');
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'Проверка отправки email...';
    }
}
