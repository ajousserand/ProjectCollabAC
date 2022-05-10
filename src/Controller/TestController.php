<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    public function __construct( AccountRepository $accountRepository, PaginatorInterface $paginator)
    {
        $this->accountRepository = $accountRepository;
        $this->paginator = $paginator;
    }

    #[Route('/test', name: 'app_test')]
    public function index(Request $request): Response
    {
        $qb = $this->accountRepository->getQbAll();
        $pagination =$this->paginator->paginate(
            $qb,
            $request->query->getInt('page',1,),
            9
        );
       
        return $this->render('test/index.html.twig', [
           'pagination' => $pagination
        ]);
    }
}
