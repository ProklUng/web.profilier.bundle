<?php

namespace Prokl\WebProfilierBundle\Contract;

/**
 * Interface ProfilerGuardInterface
 *
 * @since 18.08.2021
 */
interface ProfilerGuardInterface
{
    /**
     * Можно работать или нет.
     *
     * @return bool
     */
    public function isGranted() : bool;
}