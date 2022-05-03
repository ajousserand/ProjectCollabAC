<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameDetailController extends AbstractController
{
    public function __construct(private GameRepository $gameRepository)
    {
        
    }
    #[Route('/jeux/{slug}', name: 'app_game_detail')]
    public function index(string $slug): Response
    {

        $game = $this->gameRepository->findOneBy([$slug]);
        return $this->render('game_detail/index.html.twig', [
            'gameDetail' => $game,
        ]);
    }
}
