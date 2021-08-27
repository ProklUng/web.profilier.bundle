<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Bitrix;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;

/**
 * Class RequiredPhpModules
 */
class RequiredPhpModules extends Check
{
    protected $requiredExtension = [
        'zlib',
        'curl',
        'json',
        'gd',
        'hash',
        'mbstring',
    ];

    /**
     * @inheritDoc
     */
    public function run()
    {
        $result = true;
        foreach ($this->requiredExtension as $extension) {
            if (!extension_loaded($extension)) {
                $this->logError("модуль $extension не подключен");
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
        return 'Наличие необходимых Битриксу модулей php...';
    }
}
