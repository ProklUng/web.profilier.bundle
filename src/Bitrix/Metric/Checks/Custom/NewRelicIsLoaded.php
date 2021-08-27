<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;

class NewRelicIsLoaded extends Check
{
    /**
     * @return string
     */
    public function name()
    {
        return "Проверка, что расширение newrelic загружено...";
    }

    /**
     * @return boolean
     */
    public function run()
    {
        return $this->checkPhpExtensionsLoaded('newrelic');
    }
}
