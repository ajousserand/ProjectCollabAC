<?php

namespace App\Controller;

use App\Entity\Account;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/form', name: 'app_form')]
class FormController extends AbstractController
{
    #[Route('/', name: 'app_form')]
    public function index(): Response
    {
        return $this->render('form/index.html.twig', [
            'controller_name' => 'FormController',
        ]);
    }


    #[Route('/create', name: 'app_form')]
    public function create(Request $request, EntityManager $em)
    {
        $form = $this->createForm(FormType::class, new Account);
        $form->handleRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
        }

        return   $this->render('create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
