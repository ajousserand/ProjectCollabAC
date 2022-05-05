<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateurs')]
class UserController extends AbstractController
{

    public function __construct(private AccountRepository $accountRepository)
    {
    }


    #[Route('/{name}', name: 'st_user_show')]
    public function show(string $name): Response
    {
        $userEntity = $this->accountRepository->getAccountByName($name);
        // dd($this->accountRepository->getAccountByName($name));
        return $this->render('user/show.html.twig', [
            'user' =>  $userEntity,
            'library' => true,
        ]);
    }
}
