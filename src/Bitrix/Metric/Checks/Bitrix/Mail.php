<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;
use CSiteCheckerTest;

/**
 * Класс для тестирования отправки писем
 * Class Mail
 * @package Prokl\WebProfilierBundle\Bitrix\Metric\Custom
 */
class Mail extends Check
{
    /**
     * @inheritDoc
     */
    public function run()
    {
        $defaultMailResult = false;
        $largeMailResult = false;
        if (function_exists('mail')) {
            $emailTo = 'hosting_test@bitrixsoft.com';
            $subject = 'testing mail server';
            $message = 'testing mail server';
            $headers = 'From: webmaster@example.com' . "\r\n" .
                'Reply-To: webmaster@example.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            $defaultMailResult = mail($emailTo, $subject, $message, $headers);
            if (!$defaultMailResult) {
                $this->logError('Почтовый сервер не настроен');
            }

            $largeMailResult = (new CSiteCheckerTest)->check_mail_big();
            if (!$largeMailResult) {
                $this->logError('Отправка большого почтового сообщения не удалась');
            }
        }

        return $defaultMailResult && $largeMailResult;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'Проверка почтового сервера...';
    }
}
