<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use TicketChainer\ApiBundle\Model\Game;
use TicketChainer\ApiBundle\Model\Order;
use TicketChainer\ApiBundle\Model\TicketStock;
use TicketChainer\ApiBundle\Repository\Core\RepositoryProvider;


class TicketsController extends AbstractController
{
    /**
     * @Route("/Tickets", name="tickets")
     */
    public function index()
    {
        return $this->render('dashboard/Ticket.html.twig');
    }
}