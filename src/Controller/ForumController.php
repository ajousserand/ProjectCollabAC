<?php

namespace App\Controller;

use App\Repository\ForumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum')]
class ForumController extends AbstractController
{
    public function __construct(private ForumRepository $forumRepository){}

    #[Route('/', name: 'app_forum')]
    public function index(): Response
    {
        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }

    #[Route('/15-25', name: 'app_forum')]
    public function forum_q_v(): Response
    {
        $forum = $this->forumRepository->getForum('Forum 15-25');

        return $this->render('forum/15_25.html.twig', [
            'controller_name' => 'ForumController',
            'forum'=>$forum
        ]);
    }


}
