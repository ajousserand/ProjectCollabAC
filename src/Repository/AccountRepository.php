<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join as ExprJoin;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function add(Account $account, bool $flush = true): void
    {
        $this->_em->persist($account);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Account $account, bool $flush = true): void
    {
        $this->_em->remove($account);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getAccountByName(string $name): ?Account
    {
        // Genre::class, 'genre', ExprJoin::WITH, 'genre.games = g.genres'
        return $this->createQueryBuilder('a')
            ->select('a', 'comment', 'lib', 'g', 'gc')
            ->join('a.comments', 'comment')
            ->join('a.libraries', 'lib')
            ->join('lib.game', 'g')
            ->join('comment.game', 'gc')
            ->where('a.name = :name')
            ->setParameter('name', $name)
            ->orderBy('comment.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQbAll(){
        return $this->createQueryBuilder('a');
    }

    public function getMostActiveUser(){

        return $this->createQueryBuilder('a')
        ->addSelect('COUNT(m) as counted')
        ->join('a.messages', 'm')
        ->orderBy('counted', 'DESC')
        ->groupBy('a.id')
        ->setMaxResults(1)
        ->getQuery()
        ->getSingleResult();
    }
}
