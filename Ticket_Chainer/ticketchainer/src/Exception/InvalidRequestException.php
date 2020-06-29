<?php
namespace GuzzleHttp\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;


class InvalidRequestInterface extends \InvalidArgumentException implements RequestExeptionInterface, GuzzleException {
    private $request;

    public function __construct(RequestInterface $request, string $message)
    {
        $this->request = $request;
        parent::__construct($message);
    }

    public function getRequest(): RequestInterface {
        return $this->request;
    }
}
?>