<?php

namespace Prokl\WebProfilierBundle\Controller;

use Prokl\WebProfilierBundle\EraserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EraserDataController
 * @since 17.08.2021
 */
class EraserDataController extends AbstractController
{
    /**
     * @var EraserData $eraser Eraser.
     */
    private $eraser;

    /**
     * @param EraserData $eraser Eraser.
     */
    public function __construct(
        EraserData $eraser
    ) {
        $this->eraser = $eraser;
    }

    /**
     * @return Response
     */
    public function action() : Response
    {
        if (!$GLOBALS['USER']->isAdmin()) {
            throw $this->createAccessDeniedException('Only admins allowed!');
        }

        $this->eraser->clear();

        return new Response(json_encode(['result' => 'OK']));
    }
}