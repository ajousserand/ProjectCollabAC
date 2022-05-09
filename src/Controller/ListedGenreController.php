<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/genre')]
class ListedGenreController extends AbstractController
{

    public function __construct(private GenreRepository $genreRepository, private EntityManagerInterface $em ){}

    #[Route('/', name: 'app_genre')]
    public function index(): Response
    {
        $genreEntities = $this->genreRepository->findAll();

        return $this->render('listed_genre/index.html.twig', [
            'controller_name' => 'ListedGenreController',
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

    #[Route('delete-genre', name:'app_genre_delete')]
    public function delete(){
        
    }

    
}
