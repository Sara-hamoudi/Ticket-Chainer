<?php


class Client extends AbstractClient implements ClientInterface
{


    public function get(string $uri, array $item = [], array $options = [])
    {
        $options = array_merge($options, ['item' => $item]);
        return $this->sendHttpRequest('GET', $uri, $options);
    }

    public function post(string $uri, string $payload = '', array $options = [])
    {
        $options = array_merge($options, ['body' => $payload]);
        return $this->sendHttpRequest('POST', $uri, $options);
    }


    public function update(string $uri, string $payload = '', array $options = [])
    {
        $options = array_merge($options, ['body' => $payload]);
        return $this->sendHttpRequest('PUT', $uri, $options);
    }

    public function delete(string $uri, array $options = [])
    {
        return $this->sendHttpRequest('DELETE', $uri, $options);
    }
}

?>