<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\AccountRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    

    public function __construct(private PaginatorInterface $paginator, private AccountRepository $accountRepository){
            
    }

    #[Route('/test', name: 'app_test')]
    public function index(Request $request):Response
    {

        $qb = $this->accountRepository->getQbAll();

        $pagination = $this->paginator->paginate(
            $qb, 
            $request->query->getInt('page', 1), 
            10
        );

        // $publisherEntity = new Publisher;

        // $form = $this->createForm(PublisherType::class, $publisherEntity);
        // $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid()){
        //     $publisherEntity->setCreatedAt( new DateTime('now'));
        //     dump($publisherEntity);
        // }

        // dump($this->getUser());
        // return $this->render('test/index.html.twig', [
        //     'controller_name' => 'TestController',
        //     'form' => $form->createView(),
        // ]);

        // $user = $this->getUser();
      
        return $this->render('test/index.html.twig', [
            'pagination'=>$pagination
        ]);
    }
}
