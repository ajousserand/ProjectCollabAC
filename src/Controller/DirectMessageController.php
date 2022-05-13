<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\DirectMessage;
use App\Form\DirectMessageType;
use App\Repository\AccountRepository;
use App\Repository\DirectMessageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DirectMessageController extends AbstractController
{

    public function __construct( private AccountRepository $accountRepository,
                                private DirectMessageRepository $dmRepository,
                                private EntityManagerInterface $em){

    }

    #[Route('/direct_message', name: 'app_direct_message')]
    public function index(): Response
    {
        /** @var Account */
        $user = $this->getUser();

        if($user){
            $dmEntities = $this->dmRepository->getAllDirectMessages($user->getId());
        }else{
            return $this->render('direct_message/index.html.twig', [
                'user'=>$user,
            ]);
        }
        

        return $this->render('direct_message/index.html.twig', [
            'directMessages'=> $dmEntities,
            'user'=>$user,
        ]);
    }

    
    #[Route('/direct_message/send', name: 'app_dm_send')]
    public function send(Request $request): Response
    {
        $user = $this->getUser();
        $directMessage = new DirectMessage;
        
        $form = $this->createForm(DirectMessageType::class, $directMessage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            dump($form->get('email')->getData());
            $mail=$form->get('email')->getData();
            $receivingAccount = $this->accountRepository->findOneBy(['email'=>$mail]);

            if($receivingAccount){
                $directMessage->setCreatedAt(new DateTime());
                $directMessage->setCreatedBy($user);
                $directMessage->setHasBeenSeen(FALSE);
                $directMessage->setReceiver($receivingAccount);
                $this->em->persist($directMessage);
                $this->em->flush();
                return $this->redirectToRoute('app_dm_conversation', ['id'=>$receivingAccount->getId()]);
            }else{
                $form->addError($error = new FormError("L'utilisateur est introuvable"));
                return $this->render('direct_message/send.html.twig', [
                    'user'=>$user,
                    'form'=>$form->createView(),
                    'error'=>$error->getMessage(),
                ]);
            }
        }

        return $this->render('direct_message/send.html.twig', [
            'user'=>$user,
            'form'=>$form->createView()
        ]);
    }



    #[Route('/direct_message/send/{id}', name: 'app_dm_conversation')]
    public function sendAgain(Account $account,Request $request): Response
    {
        $user = $this->getUser();
        $conversation = $this->dmRepository->getDirectMessageByUser($user, $account->getId());
        // dd($conversation);

        
        $directMessage = new DirectMessage;
        
        $form = $this->createForm(DirectMessageType::class, $directMessage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $directMessage->setCreatedAt(new DateTime());
            $directMessage->setCreatedBy($user);
            $directMessage->setHasBeenSeen(FALSE);
            $this->em->persist($directMessage);
            $this->em->flush();
            return $this->redirectToRoute('app_dm_conversation', ['id'=>$account->getId()]);
        }

        return $this->render('direct_message/send.html.twig', [
            'user'=>$user,
            'conversation'=>$conversation,
            'formConversation'=>$form->createView()
        ]);
    }
}
