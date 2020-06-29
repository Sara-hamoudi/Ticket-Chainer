<?php 

interface ClientInterface {

    public function get(string $uri, array $item = [], array $options = []);
    public function post(string $uri, string $payload = '', array $options = []);
    public function put(string $uri, string $payload = '', array $options = []);
    public function delete(string $uri, array $options = []);
}

?>