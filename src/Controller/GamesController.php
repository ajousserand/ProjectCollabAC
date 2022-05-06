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
        $relatedGame = $this->gameRepository->getRelatedGames($game);
        return $this->render('game_detail/show.html.twig', [
            'gameDetail' => $game,
            'relatedGame' => $relatedGame,

        ]);
    }

    #[Route('/jeux/genre/{slug}', name: 'st_games_genre')]
    public function indexGenre(string $slug): Response
    {
        $gamePerGenre = $this->gameRepository->getGenreBySlug($slug);
        return $this->render('genre/index.html.twig', [
            'gameDetail' => $gamePerGenre,
            'slug' => $slug
        ]);
    }

    #[Route('/jeux/langue/{slug}', name: 'st_games_langue')]
    public function indexLangue(string $slug): Response
    {
        $gamePerLangue = $this->gameRepository->getLangueBySlug($slug);
        return $this->render('langue/index.html.twig', [
            'gameDetail' => $gamePerLangue,
            'slug' => $slug
        ]);
    }

    #[Route('/jeux/{slug}/commentaires', name: 'st_games_comments')]
    public function indexComments(string $slug): Response
    {
        $game = $this->gameRepository->getGameBySlug($slug);

        return $this->render('comment/index.html.twig', [
            'gameDetail' => $game,
            'slug' => $slug
        ]);
    }

    #[Route('/jeux/rechercher/{value}', name: 'app_index_2')]
    public function index2($value)
    {
        $response = $this->gameRepository->getGameWithSearch($value);
        return $this->render('games/index.html.twig', [
            'gameEntities' => $response
        ]);
    }
}
