<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;

class ApacheModules extends Check
{
    private $blacklist = [
        'mod_security',
        'mod_dav',
        'mod_dav_fs',
    ];

    /**
     * @inheritDoc
     */
    public function run()
    {
        if (!function_exists('apache_get_modules')) {
            return true;
        }

        $result = true;
        $loadedModules = apache_get_modules();
        foreach ($this->blacklist as $module) {
            if (in_array($module, $loadedModules)) {
                $this->logError("Необходимо выключить модуль $module");
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'Проверка модулей apache...';
    }
}
