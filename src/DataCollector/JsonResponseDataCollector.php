<?php

namespace Prokl\WebProfilierBundle\DataCollector;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;

/**
 * Class JsonResponseDataCollector
 *
 * @since 19.08.2021
 */
class JsonResponseDataCollector extends DataCollector implements LateDataCollectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $typeResponse = $response->headers->get('Content-Type', '');
        if (stripos($typeResponse, 'json') === false) {
            $this->data = [
                'method' => $request->getMethod(),
                'acceptable_content_types' => $request->getAcceptableContentTypes(),
                'response_content' => [],
                'request_query' => $request->query->all(),
                'request_request' => $request->request->all(),
                'request_files' => $request->files->all(),
                'request_headers' => $request->headers->all(),
                'request_server' => $request->server->all(),
                'request_cookies' => [],
                'response_headers' => [],
                'response_cookies' => [],
            ];
            return;
        }

        $content = (string)$response->getContent();

        $responseCookies = [];
        foreach ($response->headers->getCookies() as $cookie) {
            $responseCookies[$cookie->getName()] = $cookie;
        }

        $this->data = [
            'method' => $request->getMethod(),
            'acceptable_content_types' => $request->getAcceptableContentTypes(),
            'response_content' => $content ? (array)json_decode($content, true) : [],
            'request_query' => $request->query->all(),
            'request_request' => $request->request->all(),
            'request_files' => $request->files->all(),
            'request_headers' => $request->headers->all(),
            'request_server' => $request->server->all(),
            'request_cookies' => $request->cookies->all(),
            'response_headers' => $response->headers->all(),
            'response_cookies' => $responseCookies,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return 'json_response';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }

    /**
     * @inheritDoc
     */
    public function lateCollect()
    {
        $this->data = $this->cloneVar($this->data);
    }

    /**
     * @return ParameterBag
     */
    public function getResponseContent()
    {
        if ($this->data['response_content']) {
            return new ParameterBag($this->data['response_content']->getValue());
        }

        return new ParameterBag();
    }

    /**
     * @return ParameterBag
     */
    public function getRequestQuery()
    {
        if ($this->data['request_query']) {
            return new ParameterBag($this->data['request_query']->getValue());
        }

        return new ParameterBag();
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->data['method'];
    }

    /**
     * @return array
     */
    public function getAcceptableContentTypes()
    {
        return $this->data['acceptable_content_types'];
    }

    /**
     * @return ParameterBag
     */
    public function getResponseHeaders()
    {
        if ($this->data['response_headers']) {
            return new ParameterBag($this->data['response_headers']->getValue());
        }

        return new ParameterBag();
     }

    /**
     * @return ParameterBag
     */
    public function getResponseCookies()
    {
        if ($this->data['response_cookies']) {
            return new ParameterBag($this->data['response_cookies']->getValue());
        }

        return new ParameterBag();
    }
}