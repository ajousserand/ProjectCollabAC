<?php

namespace App\Controller;

use App\Form\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

       
        return $this->render('search/index.html.twig',[
            'form'=>$form->createView()
        ]);
        

    }
        
}
