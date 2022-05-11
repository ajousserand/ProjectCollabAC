<?php

namespace App\Command;

use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:set-admin',
    description: 'Add a short description for your command',
)]
class SetAdminCommand extends Command
{

    public function __construct(private AccountRepository $accountRepository, private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'email for become admin');
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $accountEntity = $this->accountRepository->findOneBy(['email'=> $email]);
   

        if($accountEntity == null){
            $output->writeln('Cet utilisateur est introuvable');
            return Command::FAILURE;
        }else {
            $accountEntity->setRoles(["ROLE_ADMIN"]);
            $this->em->persist($accountEntity);
            $this->em->flush();
            $output->writeln("L'utilisateur dont l'email est ".$email." est devenu admin");
            return Command::SUCCESS;
        }
        
    }
}
