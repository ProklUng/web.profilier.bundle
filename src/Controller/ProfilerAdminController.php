<?php

namespace Prokl\WebProfilierBundle\Controller;

use CUser;
use Prokl\WebProfilierBundle\Contract\DataFileHandlerInterface;
use Prokl\WebProfilierBundle\Extractor\ProfileExtractor;
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
     * @var CUser $user Битриксовый $USER.
     */
    private $user;

    /**
     * @var ProfileExtractor $profileExtractor
     */
    private $profileExtractor;

    /**
     * @param Environment              $twig             Twig.
     * @param DataFileHandlerInterface $dataFileHandler  Обработчик файлов профайлера.
     * @param ProfileExtractor         $profileExtractor
     * @param CUser                   $user             Битриксовый $USER.
     * @param string                   $template         Шаблон страницы.
     */
    public function __construct(
        Environment $twig,
        DataFileHandlerInterface $dataFileHandler,
        ProfileExtractor $profileExtractor,
        CUser $user,
        string $template
    ) {
        $this->twig = $twig;
        $this->template = $template;
        $this->dataFileHandler = $dataFileHandler;
        $this->profileExtractor = $profileExtractor;
        $this->user = $user;
    }

    /**
     * @param Request $request Request.
     *
     * @return Response
     * @throws LoaderError | RuntimeError | SyntaxError Ошибки Твига.
     * @throws RuntimeException                         Ошибки доступа.
     */
    public function token(Request $request) : Response
    {
        $token = $request->query->get('token');
        if (!$token) {
            throw $this->createAccessDeniedException('Only with type of token allowed!');
        }

        if ($token === 'latest') {
            $token = $this->profileExtractor->latestToken();
            if (!$token) {
                throw new \RuntimeException('Cannot get latest token.');
            }
        }

        $data = $this->profileExtractor->extractByToken($token);

        $content = $this->processData($data);

        return new Response($content);
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
        if (!$this->user->isAdmin()) {
            throw $this->createAccessDeniedException('Only admins allowed!');
        }

        $data = $this->dataFileHandler->read();

        $content = $this->processData($data);

        return new Response($content);
    }

    /**
     * @param array $data Данные для рендеринга.
     *
     * @return string
     * @throws LoaderError | RuntimeError | SyntaxError Ошибки Твига.
     */
    private function processData(array $data) : string
    {
        $meta = [];
        foreach ($data as $key => $url) {
            foreach ($url as $collectorName => $values) {
                if ($values['template']) {
                    // ToDo Временный костыль против глюков с Твигом.
                    if ($values['template'] !== '/collectors/twig.html.twig') {
                        $data[$key][$collectorName]['template'] = $this->twig->render($values['template'], $values);
                    }
                }

                $meta[$key]['date'] = $data[$key][$collectorName]['date'];
                unset($data[$key]['date']);
            }
        }

        return $this->twig->render($this->template, ['meta' => $meta, 'data' => $data, 'empty' => empty($data)]);
    }
}