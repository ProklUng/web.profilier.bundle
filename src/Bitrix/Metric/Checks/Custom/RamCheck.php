<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;

/**
 * Класс для проверки свободной оперативной памяти
 * Class RamCheck
 * @package Prokl\WebProfilierBundle\Bitrix\Metric\Custom
 */
class RamCheck extends Check
{
    /** @var int $limitBytes - Минимальный порог для свободной памяти (в килобайтах) */
    private $limitKiloBytes;

    /** @var int $limitBytes - Минимальный порог для свободной памяти (в мегабайтах) */
    private $limitMegaBytes;

    /**
     * RamCheck constructor.
     *
     * @param int $limit - Минимальный порог для свободного места (в мегабайтах)
     */
    public function __construct($limit)
    {
        $this->limitMegaBytes = $limit;
        $this->limitKiloBytes = $limit * 1024;
    }

    /**
     * Запускаем проверку
     *
     * @return boolean
     */
    public function run()
    {
        /** @var bool $result - Результат проверки */
        $result = true;
        /** @var resource $systemFile - Файл с информацией о памяти */
        $systemFile = fopen('/proc/meminfo', 'r');
        if (!$systemFile) {
            $this->skip('Не удалось открыть файл /proc/meminfo');
        }

        $memory = 0;
        while ($line = fgets($systemFile)) {
            $pieces = [];
            if (preg_match('/^MemFree:\s+(\d+)\skB$/', $line, $pieces)) {
                $memory = $pieces[1];
                break;
            }
        }
        fclose($systemFile);

        if ($memory < $this->limitKiloBytes) {
            $this->logError('На сервере мало свободной оперативной памяти (' . intval($memory / 1024) . ' < ' . $this->limitMegaBytes . ' мб)');
            $result = false;
        }

        return $result;
    }

    /**
     * Получаем название проверки
     *
     * @return string
     */
    public function name()
    {
        return 'Проверка свободной оперативной памяти...';
    }
}
