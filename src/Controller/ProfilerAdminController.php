<?php

namespace Prokl\WebProfilierBundle\Controller;

use Prokl\WebProfilierBundle\Contract\DataFileHandlerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class ProfilerAdminController
 * @package Prokl\WebProfilierBundle\Controller
 *
 * @since 17.08.2021
 */
class ProfilerAdminController extends AbstractController
{
    /**
     * @var Environment $twig Twig.
     */
    private $twig;

    /**
     * @var DataFileHandlerInterface $dataFileHandler
     */
    private $dataFileHandler;

    /**
     * @var string $template Твиговский шаблон страницы.
     */
    private $template;

    /**
     * @param Environment              $twig            Twig.
     * @param DataFileHandlerInterface $dataFileHandler Обработчик файлов профайлера.
     * @param string                   $template        Шаблон страницы.
     */
    public function __construct(
        Environment $twig,
        DataFileHandlerInterface $dataFileHandler,
        string $template
    ) {
        $this->twig = $twig;
        $this->template = $template;
        $this->dataFileHandler = $dataFileHandler;
    }

    /**
     * @param Request $request Request.
     *
     * @return Response
     * @throws LoaderError | RuntimeError | SyntaxError Ошибки Твига.
     * @throws RuntimeException                         Ошибки доступа.
     */
    public function action(Request $request) : Response
    {
        if (!$GLOBALS['USER']->isAdmin()) {
            throw $this->createAccessDeniedException('Only admins allowed!');
        }

        $data = $this->dataFileHandler->read();

        foreach ($data as $key => $url) {
            foreach ($url as $collectorName => $values) {
                if ($values['template']) {
                    $data[$key][$collectorName]['template'] = $this->twig->render($values['template'], $values);
                }
            }
        }

        $content = $this->twig->render($this->template, ['data' => $data, 'empty' => empty($data)]);

        return new Response($content);
    }
}
