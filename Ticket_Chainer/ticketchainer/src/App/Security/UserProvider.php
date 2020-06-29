<?php

class JWTUser implements JWTUserInterface
{
    private $username;

    private $roles;

    public function __construct($username, array $roles = [])
    {
        $this->username = $username;
        $this->roles    = $roles;
    }

    public static function createFromPayload($username, array $payload)
    {
        if (isset($payload['roles'])) {
            return new static($username, (array) $payload['roles']);
        }

        return new static($username);
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function getSalt()
    {
    }
    public function eraseCredentials()
    {
    }
}