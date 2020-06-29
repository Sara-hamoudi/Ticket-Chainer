<?php

use GuzzleHttp\ClientInterface as GuzzleHttpClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractClient
{

    private static $base_path = '/api/v1';

    private $httpClient;
    
    private $params;

    public function __construct(GuzzleHttpClientInterface $httpClient, ParameterBagInterface $params)
    {
        $this->httpClient = $httpClient;
        $this->params = $params;
    }

    public function sendHttpRequest(string $method, string $uri, array $options = [])
    {
        try {
            $request = new Request($method, $this->getFullPath($uri), $this->getHeaders());
            $response = $this->httpClient->send(
                $request,
                $options
            );
            return $response;
        } catch (ClientException $exception) {
            throw new HttpException($exception->getCode(), $exception->getResponse()->getBody()->getContents(), $exception);
        }
    }

    public function getFullPath(string $uri)
    {
        return self::$base_path . $uri;
    }

    public function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->params->get('app_jwt')
        ];
    }
}
?>