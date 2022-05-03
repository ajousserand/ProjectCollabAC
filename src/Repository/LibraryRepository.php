<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Functions\SumFunction;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Library|null find($id, $lockMode = null, $lockVersion = null)
 * @method Library|null findOneBy(array $criteria, array $orderBy = null)
 * @method Library[]    findAll()
 * @method Library[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    public function add(Library $library, bool $flush = true): void {
        $this->_em->persist($library);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Library $library, bool $flush = true): void {
        $this->_em->remove($library);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function gameByPlayed() {
       
        return $this->createQueryBuilder('l')
            ->select('SUM(game_time)','library','game')
            ->join('l.game','game')
            ->orderBy('SUM(game_time)','DESC')
            ->setMaxResults(9)
            ->getQuery()->getResult();
    }
}
