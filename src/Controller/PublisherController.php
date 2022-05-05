<?php

namespace App\Controller;

use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/editeurs')]
class PublisherController extends AbstractController
{
    #[Route('/', name: 'app_publisher')]
    public function index(PublisherRepository $publisherRepository): Response
    {
        return $this->render('publisher/index.html.twig', [
            'publishers' => $publisherRepository->getPublishersAll(),
        ]);
    }

    #[Route('/{slug}', path: 'app_publisher_show')]
    public function show(PublisherRepository $publisherRepository, $slug)
    {
        $publisherEntity = $publisherRepository->getPublisherOne($slug);
        $nbGames = count($publisherEntity->getGames());

        return $this->render('publisher/show.html.twig', [
            "publisher" => $publisherEntity,
            "nbGames" => $nbGames,
        ]);
    }
}
