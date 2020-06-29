<?php
namespace App\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

class UserProvider implements UserProviderInterface
{
    public function login(string $email, string $password, $privilegedUser = false){
        $options = ['auth' => [$email, $password]];
        if ($privilegedUser) {
            $options['headers'] = ['X-Privileged-User' => 1];
        }
        $response = $this->client->post('/authentication/token', '', $options);
        $content = $response->getBody()->getContents();
        $data = $this->serializer->decode($content, 'json');
        $decoded = JWT::decode($data['token'], $this->jwtSecret, array('HS256'));
        $payload = (array)$decoded;
        $token = new TokenResponse();
        $token->setToken($data['token'])
            ->setPayload($payload)
            ->setUserId((string)$payload['id'])
            ->setIsPrivileged($payload['is_privileged'])
            ->setRoles(new ArrayCollection($payload['roles']));
        return $token;
    }

    public function getUsernameForApiKey($apiKey, Request $request)
    {
        $username = $request->request->get('_username');
        return $username;
    }

    public function loadUserByUsername($credentials)
    {
        return $this->securityService->authenticate($credentials['email'], $credentials['password']);
    }

    public function authenticate(string $email, string $password)
    {
        $response = $this->client->post('/api/v1/authentication/token', [], ['auth' => [$email, $password]]);
        return $response;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
        throw new \Exception('TODO: fill in refreshUser() inside '.__FILE__);
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}