<?php

namespace App\Command;

use App\Repository\AccountRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:give-password',
    description: 'Add a short description for your command',
)]
class GivePasswordCommand extends Command
{

    public function __construct(private AccountRepository $accountRepository, private EntityManagerInterface $em){
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
        $accountEntities = $this->accountRepository->findAll();
        if($accountEntities == []){
            $output->writeln('Aucune personne !');
            return Command::FAILURE;
        }else{
            foreach($accountEntities as $user){
                $user->setPassword('$2y$13$VTL2HjJCwzVc.J69J8Kzwu2U0Jiy3Bsptbz6YoQQkqM6C2vBKvdrq');
                $this->em->persist($user);
            }
            $this->em->flush();
            $output->writeln("Nous avons fait une modification sur ". count($accountEntities)." compte.");
            return Command::SUCCESS;
        }
    }
}
