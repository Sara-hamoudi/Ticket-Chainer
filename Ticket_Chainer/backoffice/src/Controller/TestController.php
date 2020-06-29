<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use TicketChainer\ApiBundle\Model\Game;
use TicketChainer\ApiBundle\Model\Order;
use TicketChainer\ApiBundle\Model\TicketStock;
use TicketChainer\ApiBundle\Repository\Core\RepositoryProvider;

/**
 * @Route("/tests", name="test_")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/serializer", methods={"GET"}, name="test_serializer")
     */
    public function testNormalizer(RepositoryProvider $provider, SerializerInterface $serializer)
    {

        $order = $provider->getRepository(Order::class)->findByPk(1);
        dump($order);
        $game = $provider->getRepository(Game::class)->findByPk(1);
        dump($game);

        $response = $provider->getRepository(Game::class)->update($game);
        dump($response);

        $response = $provider->getRepository(TicketStock::class)->find();
        $pagination = $provider->getRepository(TicketStock::class)->getPaginationInfo();
        dump($pagination, $response);


        $options = [
            'normalization_context' =>
                [
                    'fetch_nested' => [
                        Game::class => [
                            'opponent',
                            'club'
                        ]

                    ]
                ]
        ];
        $response = $provider->getRepository(Game::class)->find([], $options);
        dump($response);


        $response = $provider->getRepository(TicketStock::class)->findOne(['filter' => ['clubId' => 1]]);
        dump($response);


        $response = $provider->getRepository(TicketStock::class)
            ->findOne(['filter' => ['clubId' => 1]], ['raw_response'=>true]);
        dump($response);

        die;

    }

    /**
     * @Route("/test2", methods={"GET"}, name="test2")
     */
    public function test2()
    {

    }

}