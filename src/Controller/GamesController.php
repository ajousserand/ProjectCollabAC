<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
    public function __construct(private GameRepository $gameRepository)
    {
    }

    #[Route('/jeux', name: 'st_games')]
    public function index(): Response
    {
        $gameEntities = $this->gameRepository->findAll();

        return $this->render('games/index.html.twig', [
            'controller_name' => 'GamesController',
            'gameEntities' => $gameEntities
        ]);
    }
}
