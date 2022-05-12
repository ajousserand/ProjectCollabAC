<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use App\Form\MessageType;
use App\Form\TopicType;
use App\Repository\AccountRepository;
use App\Repository\ForumRepository;
use App\Repository\MessageRepository;
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
                                private AccountRepository $accountRepository,
                                private TopicRepository $topicRepository,
                                private MessageRepository $messageRepository,
                                private EntityManagerInterface $em){}


    ########################## Forums ###################################################

    //Index

    #[Route('/', name: 'app_forum')]
    public function index(): Response
    {
        $forumEntities = $this->forumRepository->findAll();

        $forumTopTendance = $this->forumRepository->getTopForumMessage()[0];
        $forumGold = $this->forumRepository->getGoldForum()[0];
        $NAForum = $this->forumRepository->getNAForum()[0];
        $nbMessageTopForum = $this->forumRepository->getTopForumMessage()["counted"];
        $nbMessageGoldForum = $this->forumRepository->getGoldForum()["counted"];
        $nbMessageNAForum = $this->forumRepository->getNAForum()["counted"];
        $mostActiveUser= $this->accountRepository->getMostActiveUser()[0];
        $nbMessageMAU = $this->accountRepository->getMostActiveUser()["counted"];
        dump($mostActiveUser);

        return $this->render('forum/index.html.twig', [
            'forums' =>$forumEntities,
            'topForum'=>$forumTopTendance,
            'needAttentionForum' =>$NAForum,
            'goldForum'=>$forumGold,
            'nbMessageTop'=>$nbMessageTopForum,
            'nbMessageNA'=>$nbMessageNAForum,
            'nbMessageGold'=>$nbMessageGoldForum,
            'mostActiveUser'=>$mostActiveUser,
            'nbMessageMAU'=>$nbMessageMAU,
        ]);
    }

    //Show

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


    ############################# Topics ################################################

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
            'user'=>$this->getUser(),
            'topic'=>$topic,
            'form'=> $form->createView(),
        ]);
    }

    ################################# Messages ###########################################

    #[Route('{name}/topic/{topic}/edit_message/{message}', name: 'app_message_edit')]
    public function messageEdit($name, $topic, Message $message, Request $request)
    {
        $topicEntity = $this->topicRepository->find($topic);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($message);
            $this->em->flush();
            return $this->redirectToRoute('app_topic_show', ["name"=>$name, 'id'=>$topic]);
        }
        
        return $this->render('forum/topic_show.html.twig', [
            'user'=>$this->getUser(),
            'topic'=>$topicEntity,
            'formEdit'=>$form->createView()
        ]);
        
    }

    
    
    #[Route('{name}/topic/{topic}/delete_message/{message}', name: 'app_message_delete')]
    public function deleteMessage($name, $topic, Message $message)
    {
        $this->em->remove($message);
        $this->em->flush();
        return $this->redirectToRoute('app_topic_show', ["name"=>$name, 'id'=>$topic]);
        
    }



}
