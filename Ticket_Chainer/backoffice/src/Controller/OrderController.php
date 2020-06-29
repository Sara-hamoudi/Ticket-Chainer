<?php

namespace App\Controller;

use App\DataTable\ResultList;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use TicketChainer\ApiBundle\Enum\OrderStatus;
use TicketChainer\ApiBundle\Model\Game;
use TicketChainer\ApiBundle\Model\Order;
use TicketChainer\ApiBundle\Model\OrderLine;
use TicketChainer\ApiBundle\Repository\Core\RepositoryProvider;

/**
 * @Route("/orders", name="order_")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list()
    {
        return $this->render('order/list.html.twig');
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


        $order = $provider->getRepository(Order::class)->findByPk($id);

        return $this->render('order/show.html.twig', ['order' => $order]);
    }

    /**
     * @Route("/listData", name="list_data",methods={"GET"},  options={"expose"=true})
     */
    public function listData(
        NormalizerInterface $normalizer,
        RepositoryProvider $provider
    )
    {

        $options = [
            'normalization_context' =>
                [
                    'fetch_nested' => [
                        Order::class => [
                            'user'
                        ]

                    ]
                ]
        ] ;
        $orders = $provider->getRepository(Order::class)->find([
            'limit' => 10000
        ],$options);


        $orders = $normalizer->normalize($orders);


        foreach ($orders as &$order) {
            $user = $order['user'];
            $order['user'] = [
                'displayName' =>$this->render(
                    'order/_user_display_name_row.html.twig', [
                        'user'=>$user
                    ]
                )->getContent()
            ];
            $order['totalAmount'] = $order['totalAmount']. ' €';

            $status = $order['status'];

            $order['status']= [
                'value' => $status,
                'display' => $this->render(
                    'order/_status_row.html.twig', [
                        'status'=>$status
                    ]
                )->getContent()
            ];


        }
        return new JsonResponse(['data' => $orders]);
    }


}