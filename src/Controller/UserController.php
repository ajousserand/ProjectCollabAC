<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\UserType;
use App\Repository\AccountRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateurs')]
class UserController extends AbstractController
{

    public function __construct(private AccountRepository $accountRepository, 
                                private EntityManagerInterface $em,
                                private PaginatorInterface $paginator)
    {
    }

    #[Route('/', name: 'app_user')]
    public function index(Request $request)
    {
        $qb = $this->accountRepository->getQbAll();
        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('user/index.html.twig', [
            'users' => $this->accountRepository->findAll(),
            'pagination'=>$pagination,
        ]);
    }

    #[Route('/create', name: 'app_user_create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, new Account());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setCreatedAt(new DateTime('now'));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('form/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{name}', name: 'app_user_edit')]
    public function edit(Account $account, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('form/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $account
        ]);
    }

    #[Route('/{name}', name: 'st_user_show')]
    public function show(string $name): Response
    {
        $userEntity = $this->accountRepository->getAccountByName($name);
        return $this->render('user/show.html.twig', [
            'user' =>  $userEntity,
            'library' => true,
        ]);
    }

    #[Route('/delete/{name}', name:'app_user_delete')]
    public function delete(Account $account){

        $this->em->remove($account);
        $this->em->flush();

        return $this->redirectToRoute('app_user');
    }
}
