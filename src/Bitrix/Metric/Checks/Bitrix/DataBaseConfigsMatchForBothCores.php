<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;
use Bitrix\Main\Config\Configuration;

/**
 * Класс для проверки настроек подключения к БД
 * Class DataBaseConnectionSettings
 * @package Prokl\WebProfilierBundle\Bitrix\Metric\Custom
 */
class DataBaseConfigsMatchForBothCores extends Check
{
    /**
     * @var boolean $result Результат проверки.
     */
    private $result = true;

    /**
     * @inheritDoc
     */
    public function run()
    {
        global $DBHost, $DBName, $DBLogin, $DBPassword;
        $connectionsSettings = Configuration::getInstance()->get('connections')['default'];

        $this->check('host', $connectionsSettings['host'], $DBHost);
        $this->check('database', $connectionsSettings['database'], $DBName);
        $this->check('login', $connectionsSettings['login'], $DBLogin);
        $this->check('password', $connectionsSettings['password'], $DBPassword);

        return $this->result;
    }

    /**
     * Производим сравнение параметров из dbconn.php и .settings.php
     *
     * @param string $paramName     Название параметра.
     * @param string $settingsParam Параметр в файле .settings.php.
     * @param string $dbconnParam   Параметр в файле dbconn.php.
     *
     * @return void
     */
    private function check(string $paramName, string $settingsParam, string $dbconnParam) : void
    {
        if ($settingsParam !== $dbconnParam) {
            $this->logError(
                'Параметр ' . $paramName
                    . ' в dbconn.php не соответствует этому же параметру в .settings.php'
            );
            $this->result = false;
        }
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'Проверка совпадения параметров подключения к базе данных в старом и новом ядре...';
    }
}
