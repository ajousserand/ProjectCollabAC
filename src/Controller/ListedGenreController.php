<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/genre')]
class ListedGenreController extends AbstractController
{

    public function __construct(private GenreRepository $genreRepository, 
                                private EntityManagerInterface $em ,
                                private PaginatorInterface $paginator
                                ){}

    #[Route('/', name: 'app_genre')]
    public function index(Request $request): Response
    {
        $genreEntities = $this->genreRepository->findAll();
        $qb = $this->genreRepository->getQbAll();
        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('listed_genre/index.html.twig', [
            'controller_name' => 'ListedGenreController',
            'pagination'=>$pagination,
            'genres'=> $genreEntities,
        ]);
    }

    #[Route('/add-genre', name: 'app_genre_create')]
    public function add(Request $request){
        $genre = new Genre();

        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            $this->em->persist($genre);
            $this->em->flush();
            return $this->redirectToRoute('app_genre');
        }

        return $this->render('genre/genre_create.html.twig', [
                'form'=>$form->createView(),
        ]);
    }
    
    #[Route('/edit-genre/{name}', name: 'app_genre_edit')]
    public function edit(Genre $genre, Request $request){

        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('app_genre');
        }

        return $this->render('genre/genre_edit.html.twig', [
                'form'=>$form->createView(),
        ]);
    }

    #[Route('delete-genre/{name}', name:'app_genre_delete')]
    public function delete(Genre $genre, Request $request){

            $this->em->remove($genre);
            $this->em->flush();
            return $this->redirectToRoute('app_genre');
    }
    
}
