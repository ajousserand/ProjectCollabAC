<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListedGenreController extends AbstractController
{

    #[Route('/admin/genre', name: 'app_listed_genre')]
    public function index(GenreRepository $genreRepository): Response
    {
        $genreEntities = $this->genreRepository->findAll();

        return $this->render('listed_genre/index.html.twig', [
            'controller_name' => 'ListedGenreController',
            'genres' => $genreEntities
        ]);
    }
}
