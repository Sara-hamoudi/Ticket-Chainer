<?php

namespace App\Controller;

use App\Form\GameType;
use App\Form\TicketStockType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use TicketChainer\ApiBundle\Model\Club;
use TicketChainer\ApiBundle\Model\Game;
use TicketChainer\ApiBundle\Model\Stadium;
use TicketChainer\ApiBundle\Model\TicketStock;
use TicketChainer\ApiBundle\Repository\Core\RepositoryProvider;
use TicketChainer\ApiBundle\Repository\GameRepository;

/**
 * @Route("/games", name="game_")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="list")
     */
    public function list()
    {
        return $this->render('game/list.html.twig');
    }

    /**
     * @Route("/listData", name="list_data",methods={"GET"},  options={"expose"=true})
     */
    public function listData(
        NormalizerInterface $normalizer,
        RepositoryProvider $provider
    )
    {
        $gameCollection = $provider->getRepository(Game::class)->find([
            'limit' => 10000
        ], [
            'normalization_context' =>
                [
                    'fetch_nested' => [
                        Game::class => [
                            'opponent',
                            'club'
                        ]

                    ]
                ]
        ]);

        $data = [];
        foreach ($gameCollection as $game) {

            $ticketStock = $provider->getRepository(TicketStock::class)->findOne([
                'filter' => [
                    'gameId' => $game->getId()
                ]
            ]);

            $gameData = $normalizer->normalize($game);

            $gameData['ticketStock'] = $ticketStock ? $normalizer->normalize($ticketStock) : null;
            $data[] = $gameData;
        }


        return new JsonResponse(['data' => $data]);
    }


    /**
     * @Route("/{id}/show", name="show", methods={"GET"}, options={"expose"=true})
     */
    public function show(string $id, Request $request, RepositoryProvider $provider)
    {
        $game = $provider->getRepository(Game::class)->findByPk($id);
        return $this->render('game/show.html.twig', ['game' => $game]);
    }
    /**
     * @Route("/new", name="create", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function create(Request $request, RepositoryProvider $provider)
    {

        $club = $provider->getRepository(Club::class)->findByPk(1);
        $stadium = $provider->getRepository(Stadium::class)->findByPk(1);

        $game = new Game();
        $game->setClub($club);
        $game->setStadium($stadium);

        $gameForm = $this->createForm(
            GameType::class,
            $game,
            [
                'form_action' => $this->generateUrl('game_create')
            ]
        );

        $gameForm->handleRequest($request);
        if ($gameForm->isSubmitted() && $gameForm->isValid()) {
            $game = $gameForm->getData();
            $gameRepository = $provider->getRepository(Game::class);
            $game = $gameRepository->create($game);
            $game = $gameRepository->findByPk($game->getId());
            $gameRepository->createTicketStock($game);

            $redirectUrl = $this->generateUrl('game_edit', ['id' => $game->getId()]);
            $response = new JsonResponse();
            $response->headers->set('Redirect-To-Url', $redirectUrl);
            return $response;

        }

        return $this->render('game/form.html.twig', [
            'title' => 'Nouveau match',
            'gameForm' => $gameForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function edit(Request $request, string $id, RepositoryProvider $provider)
    {

        $formOptions = ['form_action' => $this->generateUrl('game_edit', ['id' => $id])];

        $gameRepository = $provider->getRepository(Game::class);

        $game = $gameRepository->findByPk($id);
        $ticketStock = $gameRepository->getTicketStock($game->getId());

        if (!$ticketStock) {
            $ticketStock = $gameRepository->createTicketStock($game);
        }

        $gameForm = $this->createForm(GameType::class, $game, $formOptions);
        $ticketStockForm = $this->createForm(TicketStockType::class, $ticketStock, $formOptions);

        $gameForm->handleRequest($request);
        if ($gameForm->isSubmitted() && $gameForm->isValid()) {
            $game = $gameForm->getData();
            $gameRepository->update($game);
            return new JsonResponse();
        }

        $ticketStockForm->handleRequest($request);
        if ($ticketStockForm->isSubmitted() && $ticketStockForm->isValid()) {
            $ticketStock = $ticketStockForm->getData();
            $gameRepository->updateTicketStock($ticketStock);
            return new JsonResponse();
        }

        return $this->render('game/form.html.twig', [
            'title' => 'Modifier le match',
            'gameForm' => $gameForm->createView(),
            'ticketStockForm' => $ticketStockForm->createView()
        ]);


    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"}, options={"expose"=true})
     */
    public function delete(string $id, GameRepository $gameRepository)
    {
        $response = $gameRepository->deleteByPk($id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}