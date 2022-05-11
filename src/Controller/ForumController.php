<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use App\Form\MessageType;
use App\Form\TopicType;
use App\Repository\ForumRepository;
use App\Repository\TopicRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
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
    public function forum_show(string $name): Response
    {
        $forum = $this->forumRepository->getForum($name);

        return $this->render('forum/forum_show.html.twig', [
            'controller_name' => 'ForumController',
            'forum'=>$forum,
            'user'=>$this->getUser(),
        ]);
    }


    ############################# Topic ################################################

    #[Route('/{name}/create-topic', name: 'app_topic_create')]
    public function topic_create(string $name, Request $request): Response
    {
        $forum = $this->forumRepository->getForum($name);
        $topic = new Topic;
        $user = $this->getUser();

        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $topic->setCreatedAt(new Datetime());
            $topic->setCreatedBy($user);
            $topic->setForums($forum);
            $this->em->persist($topic);
            $this->em->flush();
            return $this->redirectToRoute('app_forum_show', ['name'=>$name]);
        }

        return $this->render('forum/topic_form.html.twig', [
            'form'=> $form->createView(),
            'forum'=>$forum,
            'user'=>$user,
        ]);
    }

    #[Route('/{name}/edit-topic/{id}', name: 'app_topic_edit')]
    public function topic_edit(string $name, Topic $topic, Request $request): Response
    {
        $forum = $this->forumRepository->getForum($name);
        $user = $this->getUser();


        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($topic);
            $this->em->flush();
            return $this->redirectToRoute('app_forum_show', ['name'=>$name]);
        }

        return $this->render('forum/topic_form.html.twig', [
            'form'=> $form->createView(),
            'forum'=>$forum,
            'user'=>$user,
        ]);
    }


    #[Route('/{name}/delete-topic/{id}', name: 'app_topic_delete')]
    public function topic_delete(string $name, Topic $topic): Response
    {
        $this->em->remove($topic);
        $this->em->flush();
        return $this->redirectToRoute('app_forum_show', ['name'=>$name]);
    }





    #[Route('/{name}/topic/{id}', name: 'app_topic_show')]
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
