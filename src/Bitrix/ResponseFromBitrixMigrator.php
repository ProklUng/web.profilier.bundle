<?php

namespace Prokl\WebProfilierBundle\Bitrix;

use Bitrix\Main\Application;
use Bitrix\Main\Response;

/**
 * Class ResponseFromBitrixMigrator
 *
 * @since 23.08.2021
 */
class ResponseFromBitrixMigrator
{
    /**
     * @var \Bitrix\Main\Response\Response
     */
    private $responseBitrix;

    /**
     * @param \Bitrix\Main\Response\Response $responseBitrix
     */
    public function __construct(?\Bitrix\Main\Response\Response $responseBitrix = null)
    {
        if (!$responseBitrix) {
            $responseBitrix = Application::getInstance()->getContext()->getResponse();
        }
        
        $this->responseBitrix = $responseBitrix;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function convert() : \Symfony\Component\HttpFoundation\Response
    {
        $headers = $this->convertHeaders($this->responseBitrix->getHeaders()->toArray());
        
        $symfonyResponse = new \Symfony\Component\HttpFoundation\Response(
            $this->responseBitrix->getContent(),
            $this->responseBitrix->getStatus() ?? 200,
            $headers
        );
        
        return $symfonyResponse;
    }
    
    private function convertHeaders(array $headers) : array 
    {
        $convertedHeaders = [];
        foreach ($headers as $name => $header) {
            $name = $header['name'];
            $values = array_unique($header['values']);
            if ($values[0]) {
                $convertedHeaders[$name] = $values[0];
            }
        }

        return $convertedHeaders;
    }
}