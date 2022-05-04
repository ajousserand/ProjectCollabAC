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


    #[Route('/{name}', name: 'st_user')]
    public function index(string $name): Response
    {
        function getGameTime($time)
        {
            return round($time / 3600, 1);
        }

        if ($name) {
            $userEntity = $this->accountRepository->getAccountByName($name);
            // dd($this->accountRepository->getAccountByName($name));
            return $this->render('user/index.html.twig', [
                'controller_name' => 'UserController',
                'user' =>  $userEntity,
            ]);
        }
        return 'Pas de compte correspondant à ce pseudo';
    }
}
