<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use TicketChainer\ApiBundle\Model\User;
use TicketChainer\ApiBundle\Repository\Core\RepositoryProvider;

/**
 * @Route("/users", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list()
    {
        return $this->render('user/list.html.twig');
    }

    /**
     * @Route("/new", name="create", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function create(Request $request, RepositoryProvider $provider)
    {


    }

    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, options={"expose"=true})
     */
    public function show(string $id, Request $request, RepositoryProvider $provider)
    {

        $user = $provider->getRepository(User::class)->findByPk($id);
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/listData", name="list_data",methods={"GET"},  options={"expose"=true})
     */
    public function listData(
        NormalizerInterface $normalizer,
        RepositoryProvider $provider
    )
    {
        $users = $provider->getRepository(User::class)->find([
            'filter' => [
                'isPrivileged' => false
            ],
            'limit' => 10000
        ]);
        $data = $normalizer->normalize($users);
        return new JsonResponse(['data' => $data]);
    }


}