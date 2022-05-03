<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(private GameRepository $gameRepository, private CommentRepository $commentRepository)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $gameByPublished = $this->gameRepository->gameByPublishedAt();
        $lastComment = $this->commentRepository->lastComment();
        $mostGamePlayed = $this->gameRepository->mostPlayedGame(9);
        $mostGameBought = $this->gameRepository->mostBoughtGame(9);

        return $this->render('home/index.html.twig', [
            'lastPublishedGames' => $gameByPublished,
            'lastComments' => $lastComment,
            'mostGamePlayed' => $mostGamePlayed,
            'mostBoughtGame'=> $mostGameBought
        ]);
    }
}
