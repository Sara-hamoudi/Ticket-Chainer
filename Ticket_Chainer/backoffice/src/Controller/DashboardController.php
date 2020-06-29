<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use phpDocumentor\Reflection\DocBlock\Serializer;
use TicketChainer\ApiBundle\Security\Authentication\TokenResponse;
use App\Controller\LoginController;

class DashboardController extends AbstractController
{

    public function login(string $email, string $password)
    {
        $options = ['auth' =>[$email, $password]];
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

    public function login1(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('dashboard/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    /**
     * @Route("/dashboard", name="dashboard_index")
     */
    public function index()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://dev.api.ticketchainer.com/api/v1/authentication/token', ['auth' => [$email, $password]]);
        return $this->render('dashboard/index.html.twig');
    }
}