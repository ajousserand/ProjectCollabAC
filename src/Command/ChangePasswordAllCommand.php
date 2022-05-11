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
    name: 'change-password-all',
    description: 'Add a short description for your command',
)]
class ChangePasswordAllCommand extends Command
{

    public function __construct(private AccountRepository $accountRepository, private EntityManagerInterface $em)
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
       $accEntities = $this->accountRepository->findAll();
       $num = 0;

        foreach($accEntities as $account){
            $account->setPassword('$2y$13$GuvtaXZaOuTphdPzWtTMDeR36YSqvzcSsLm0CfoL.bkoiEpJfCbvi');
            $this->em->persist($account);
            $num++;
        }
        
        $this->em->flush();
        $output->writeln("$num account on été modifié");
        return Command::SUCCESS;
    }
}
