<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;

/**
 * Class XDebugIsNotLoaded
 */
class XDebugIsNotLoaded extends Check
{
    /**
     * @inheritDoc
     */
    public function name()
    {
        return "Проверка, что расширение xdebug не загружено...";
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        return $this->checkPhpExtensionsNotLoaded('xdebug');
    }
}
