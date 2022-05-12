<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Account;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\GameSearchType;
use App\Repository\CommentRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
    public function __construct(private GameRepository $gameRepository, 
                                private PaginatorInterface $paginator,
                                private EntityManagerInterface $em,
                                private CommentRepository $commentRepository
                            
                                ){}

    #[Route('/jeux/{slug}', name: 'st_games')]
    public function index(string $slug = "", Request $request, EntityManagerInterface $em): Response
    {
        $game = $this->gameRepository->getGameBySlug($slug);
        $user = $this->getUser();

        if ($game == null) {
            $gameEntities = $this->gameRepository->findBy([], ['publishedAt' => 'DESC']);

            $pagination = $this->paginator->paginate(
                $gameEntities,
                $request->query->getInt('page', 1),
                8
            );

            return $this->render('games/index.html.twig', [
                'gameEntities' => $gameEntities,
                'pagination'=> $pagination
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

    #[Route('/admin/games', name:'app_games_admin')]
    public function index_admin(Request $request){

        $qb = $this->gameRepository->getQbAll();
        $gameform = $this->createForm(GameSearchType::class);
        $gameform->handleRequest($request);

        if ($gameform->isSubmitted() && $gameform->isValid()) {
            $data = $gameform->getData();
            $qb = $this->gameRepository->updateQbByData($qb,$data);
            
        }
              

        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('games/index_admin.html.twig', [
            'games'=>$qb,
            'pagination' => $pagination,
            'gameForm'=> $gameform->createView()
        ]);
    }

    #[Route('/admin/games/create', name:'app_games_create')]
    public function create( Request $request){
        $game = new Game;
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($game);
            $this->em->flush();
            return $this->redirectToRoute('app_games_admin');
        }

        return $this->render('games/game_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/game/edit/{slug}', name:'app_games_edit')]
    public function edit(Game $game, Request $request){

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('app_games_admin');
        }

        return $this->render('games/game_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/game/delete/{slug}', name:'app_games_delete')]
    public function delete(Game $game){

            $this->em->remove($game);
            $this->em->flush();
            return $this->redirectToRoute('app_games_admin');
    }
}
