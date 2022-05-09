<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(Request $request): Response
    {

        $publisherEntity = new Publisher;

        $form = $this->createForm(PublisherType::class, $publisherEntity);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $publisherEntity->setCreatedAt( new DateTime('now'));
            dump($publisherEntity);
        }

        dump($this->getUser());
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'form' => $form->createView(),
        ]);
    }
}
