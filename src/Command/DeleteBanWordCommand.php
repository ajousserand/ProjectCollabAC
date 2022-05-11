<?php

namespace App\Command;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'delete-ban-word',
    description: 'Add a short description for your command',
)]
class DeleteBanWordCommand extends Command
{

    public function __construct(private MessageRepository $messageRepository, private EntityManagerInterface $em)
    {
        parent::__construct();
        
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nbMessageDelete=0;
        $bannedWord = ['Pokemon', 'Digimon', 'Barbie', 'FromSoftSuck', 'UbisoftTheBest', 'BethesdaUnderatted'];
        $messageEntities = $this->messageRepository->findAll();
        if($messageEntities === []){
            $output->writeln("Aucun message n'existe dans le forum");
            return Command::FAILURE;
        }else{
            foreach($messageEntities as $messageEntity){
                foreach($bannedWord as $word){
                    if(strpos($messageEntity->getContent(), $word) !== false){
                        $messageOwner = $messageEntity->getCreatedBy();
                        $messageOwner->setNbBanWord(++$messageOwner->getNbBanWord());
                        $this->em->remove($messageEntity);
                        $this->em->persist($messageOwner);
                        
                        $nbMessageDelete += 1;
                        break;
                    }
                    
                }
            
            }

            $this->em->flush();

            if($nbMessageDelete === 0){
                $output->writeln("Aucun message n'a été supprimé");
            }elseif($nbMessageDelete === 0){
                $output->writeln($nbMessageDelete." message a été supprimé");
            
            }else{
                $output->writeln($nbMessageDelete." messages ont été supprimés");
            }
            return Command::SUCCESS;

        }

    }
}
