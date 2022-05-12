<?php

namespace App\Controller;

use App\Form\FormType;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{

    public function __construct(private GameRepository $gameRepository)
    {
    }

    #[Route('/searchBar', name: 'app_search_bar')]
    public function searchBar(Request $request, ): Response {
        
        $formSearch = $this->createForm(FormType::class);
        $formSearch->handleRequest($request);


        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $value = $formSearch->getData()['search_value'];
            
            if ($value === null) {
                return $this->redirectToRoute('st_games');
            }
            $response = $this->gameRepository->getGameWithSearch($value);

            if(count($response) === 1){
                return $this->redirectToRoute('st_games',['slug' => $response[0]->getSlug()]);
            }elseif(count($response)=== 0){
                return $this->redirectToRoute('st_games');
            }
            return $this->redirectToroute('app_index_2',['value'=>$value]);
       
           
        }

        return $this->render('search/index.html.twig', [
            'formSearch' => $formSearch->createView(),
        ]);
    }

}
        

