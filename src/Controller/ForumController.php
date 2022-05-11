<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ForumRepository;
use App\Repository\TopicRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum')]
class ForumController extends AbstractController
{
    public function __construct(private ForumRepository $forumRepository, 
                                private TopicRepository $topicRepository,
                                private EntityManagerInterface $em){}

    #[Route('/', name: 'app_forum')]
    public function index(): Response
    {
        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }

    #[Route('/{name}', name: 'app_forum_show')]
    public function show(string $name): Response
    {
        $forum = $this->forumRepository->getForum($name);

        return $this->render('forum/forum_show.html.twig', [
            'controller_name' => 'ForumController',
            'forum'=>$forum
        ]);
    }

    #[Route('/{name}/{id}', name: 'app_topic_show')]
    public function topic_show(string $name, string $id, Request $request): Response
    {

        $topic = $this->topicRepository->getTopic($id);

        //MessageForm part 

        $topic = $this->topicRepository->getTopic($id);
        $message = new Message;

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setCreatedAt(new DateTime());
            $message->setCreatedBy($this->getUser());
            $message->setTopic($topic);
            $this->em->persist($message);
            $this->em->flush();
            return $this->redirectToRoute('app_topic_show', ['name'=> $name, 'id' => $id]);
        }

        return $this->render('forum/topic_show.html.twig', [
            'topic'=>$topic,
            'form'=> $form->createView(),
        ]);
    }

    // #[Route(name: 'app_topic_message')]
    // public function messageForm(string $name, string $id, Request $request): Response
    // {

    //     return $this->render('forum/message_form.html.twig', [
    //         'form'=> $form->createView(),
    //     ]);
    // }



}
