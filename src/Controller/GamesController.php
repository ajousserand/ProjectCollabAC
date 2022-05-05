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
        $game = $this->gameRepository->getGameBySlug($slug);
        if ($game == null) {
            $gameEntities = $this->gameRepository->findBy([], ['publishedAt' => 'DESC']);
            return $this->render('games/index.html.twig', [
                'gameEntities' => $gameEntities
            ]);
        }
        return $this->render('game_detail/show.html.twig', [
            'gameDetail' => $game,
        ]);
    }

    #[Route('/jeux/genre/{slug}', name: 'st_games_genre')]
    public function indexGenre(string $slug = ""): Response
    {
        $gamePerGenre = $this->gameRepository->getGenreBySlug($slug);
        // if ($game == null) {
        //     $gameEntities = $this->gameRepository->findBy([], ['publishedAt' => 'DESC']);
        //     return $this->render('games/index.html.twig', [
        //         'gameEntities' => $gameEntities
        //     ]);
        // }
        return $this->render('genre/index.html.twig', [
            'gameDetail' => $gamePerGenre,
        ]);
    }
}
