<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/login", name="login")
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('dashboard/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    /**
     * @Route("/", name="index1")
     */

    public function login(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        if ($request->getMethod() == 'POST') {
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');
            $response = $client->request('POST', 'http://dev.api.ticketchainer.com/api/v1/authentication/token', ['auth' => [$email, $password]]);
            echo $response->getStatusCode(); # 200
            echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
            echo $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'
            if ($response->getStatusCode() == '200') {
                return $this->render('dashboard/index.html.twig');
            }
        }else{
            return $this->render('dashboard/login.html.twig');
        }

    }
}