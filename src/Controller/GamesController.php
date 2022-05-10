<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
    public function __construct(private GameRepository $gameRepository, private CommentRepository $commentRepository)
    {
    }

    #[Route('/jeux/{slug}', name: 'st_games')]
    public function index(string $slug = "", Request $request, EntityManagerInterface $em): Response
    {
        $game = $this->gameRepository->getGameBySlug($slug);
        $user = $this->getUser();
    

        if ($game == null) {
            $gameEntities = $this->gameRepository->findBy([], ['publishedAt' => 'DESC']);
            return $this->render('games/index.html.twig', [
                'gameEntities' => $gameEntities
            ]);
        }

        $commentEntity = new Comment;

        $form = $this->createForm(CommentType::class, $commentEntity);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $commentEntity->setCreatedAt( new DateTime('now'));
            $commentEntity->setUpVotes(0);
            $commentEntity->setDownVotes(0);
            $commentEntity->setAccount($user);
            $commentEntity->setGame($game);
            $em->persist($commentEntity);
            $em->flush();
        }
        
        if($user){
        $response = $this->commentRepository->getCommentPerGamePerUser($user,$game);
        }else{
            $response= null;
        }
        $relatedGame = $this->gameRepository->getRelatedGames($game);
        return $this->render('game_detail/show.html.twig', [
            'gameDetail' => $game,
            'relatedGame' => $relatedGame,
            'response'=> $response,
            'formOpti' => $form->createView(),

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
            'gameEntities' => $response,
            'search'=>true,
            'value'=>$value
        ]);
    }
}
