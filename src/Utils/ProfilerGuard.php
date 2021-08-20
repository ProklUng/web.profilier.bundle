<?php

namespace Prokl\WebProfilierBundle\Utils;

use Prokl\WebProfilierBundle\Contract\ProfilerGuardInterface;

/**
 * Class ProfilerGuard
 *
 * @since 18.08.2021
 */
class ProfilerGuard implements ProfilerGuardInterface
{
    /**
     * @var string $env Окружение.
     */
    private $env;

    /**
     * @param string $env Окружение.
     */
    public function __construct(string $env)
    {
        $this->env = $env;
    }

    /**
     * @inheritDoc
     */
    public function isGranted() : bool
    {
        if ($this->env !== 'dev'
            ||
            $_SERVER['PHP_SELF'] === '/bitrix/tools/public_session.php' // Внутренняя хрень.
        ) {
            return false;
        }

        return true;
    }
}