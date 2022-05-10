<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/editeurs')]
class PublisherController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'app_publisher')]
    public function index(PublisherRepository $publisherRepository, ): Response
    {
        return $this->render('publisher/index.html.twig', [
            'publishers' => $publisherRepository->getPublishersAll(),
        ]);
    }

    #[Route('/create', path:'app_publisher_create')]
    public function create(Request $request){
        
        $publisher = new Publisher();

        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $publisher->setCreatedAt(new DateTime());
            $this->em->persist($publisher);
            $this->em->flush();
            return $this->redirectToRoute('app_publisher');
        }

        return $this->render('publisher/publisher_create.html.twig', [
            'form'=>$form->createView(),
        ]);

    }

    #[Route('/{slug}', path: 'app_publisher_show')]
    public function show(PublisherRepository $publisherRepository, $slug)
    {
        $publisherEntity = $publisherRepository->getPublisherOne($slug);
        $nbGames = count($publisherEntity->getGames());

        return $this->render('publisher/show.html.twig', [
            "publisher" => $publisherEntity,
            "nbGames" => $nbGames,
        ]);
    }

    #[Route('/edit/{slug}', path:'app_publisher_edit')]
    public function edit(Publisher $publisher, Request $request){

        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            return $this->redirectToRoute('app_publisher');
        }

        return $this->render('publisher/publisher_edit.html.twig', [
            'form'=>$form->createView(),
            'publisher'=>$publisher
        ]);

    }

    #[Route('delete/{slug}', name:'app_publisher_delete')]
    public function delete(Publisher $publisher){
        $this->em->remove($publisher);
        $this->em->flush();
        return $this->redirectToRoute('app_publisher');
    }
}
