<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LastGameCreatedController extends AbstractController
{
    #[Route('/last/game/created', name: 'app_last_game_created')]
    public function index(): Response
    {
        return $this->render('last_game_created/index.html.twig', [
            'controller_name' => 'LastGameCreatedController',
        ]);
    }
}
