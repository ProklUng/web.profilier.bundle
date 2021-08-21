<?php

namespace Prokl\WebProfilierBundle\Controller;

use CUser;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * Class ProfilerAdminController
 * @package Prokl\WebProfilierBundle\Controller
 *
 * @since 17.08.2021
 */
class ProfilerController extends AbstractController
{
    /**
     * @var CUser $user Битриксовый $USER.
     */
    private $user;

    /**
     * @var Profiler $profiler Profiler.
     */
    private $profiler;

    /**
     * @param CUser    $user     Битриксовый CUser.
     * @param Profiler $profiler Profiler.
     */
    public function __construct(
        CUser $user,
        Profiler $profiler
    ) {
        $this->profiler = $profiler;
        $this->user = $user;
    }

    /**
     * @param Request $request Request.
     *
     * @return Response
     * @throws RuntimeException  Ошибки доступа.
     */
    public function action(Request $request): Response
    {
        if (!$this->user->isAdmin()) {
            throw $this->createAccessDeniedException('Only admins allowed!');
        }

        $token = $request->query->get('token');

        if ('latest' === $token && $latest = current($this->profiler->find(null, null, 1, null, null, null))) {
            $token = $latest['token'];
        }

        if (!$profile = $this->profiler->loadProfile($token)) {
            return new Response(json_encode(['success' => false, 'token' => null]));
        }

        $content = json_encode(['success' => true, 'token' => $token]);

        return new Response($content);
    }
}