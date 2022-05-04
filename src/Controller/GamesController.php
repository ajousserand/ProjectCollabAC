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

    #[Route('/jeux/{slug}', name: 'st_games')]
    public function index(string $slug = ""): Response
    {
        if ($slug == "") {
            $gameEntities = $this->gameRepository->findBy([], ['publishedAt' => 'DESC']);
            return $this->render('games/index.html.twig', [
                'gameEntities' => $gameEntities
            ]);
        }

        $game = $this->gameRepository->getGameBySlug($slug);
        return $this->render('game_detail/index.html.twig', [
            'gameDetail' => $game,

        ]);
    }
}
