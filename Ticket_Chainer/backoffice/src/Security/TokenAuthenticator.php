<?php
// src/Security/TokenAuthenticator.php
namespace App\Security;

use App\Entity\User;
use App\Security\UserProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TokenAuthenticator extends AbstractGuardAuthenticator
{

    private $em;


    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
       // return $request->headers->has('X-AUTH-TOKEN');
        return 'login_route' === $request->attributes->get('_route') && $request->isMethod('POST');

        // e.g. your login system authenticates by the user's IP address
        // BAD behavior: So, you decide to *always* return true so that
        // you can check the user's IP address on every request
       // return true;
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

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
        $token = $client->request('POST', 'http://dev.api.ticketchainer.com/api/v1/authentication/token', ['auth' => [$username, $password]]);
        if ($token == 'ILuvAPIs') {
            throw new CustomUserMessageAuthenticationException(
                'ILuvAPIs is not a real API key: it\'s just a silly phrase'
            );
        }
        return [
            'token' => $request->headers->get('X-AUTH-TOKEN'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if (null === $apiToken) {
            return;
        }
        // if a User object, checkCredentials() is called
        //return $this->em->getRepository(User::class)
           // ->findOneBy(['apiToken' => $apiToken]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            $this->translator->trans($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
           'Veuillez vous Authentifier'
        ];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
