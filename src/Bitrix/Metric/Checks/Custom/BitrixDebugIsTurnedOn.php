<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;
use Bitrix\Main\Config\Configuration;

/**
 * Class BitrixDebugIsTurnedOn
 */
class BitrixDebugIsTurnedOn extends Check
{
    /**
     * @inheritDoc
     */
    public function name()
    {
        return "Проверка на exception_handling.debug = true ...";
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $config = Configuration::getInstance()->get('exception_handling');

        if (empty($config['debug'])) {
            $this->logError('Значение конфигурации exception_handling.debug должно быть true в данном окружении');
            return false;
        }

        return true;
    }
}
