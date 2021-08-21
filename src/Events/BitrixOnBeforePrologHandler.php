<?php

namespace Prokl\WebProfilierBundle\Events;

use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * Class BitrixOnBeforePrologHandler
 *
 * @since 21.08.2021
 */
class BitrixOnBeforePrologHandler
{
    /**
     * @var Profiler $profiler Profiler.
     */
    private $profiler;

    /**
     * @param Profiler $profiler Profiler.
     */
    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * @return void
     */
    public function handle() : void
    {
        if (!$GLOBALS['USER']->IsAdmin()) {
            $this->profiler->disable();
        }
    }
}