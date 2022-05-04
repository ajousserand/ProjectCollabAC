<?php

namespace App\Repository;

use App\Entity\Country;
use App\Entity\Genre;
use App\Entity\Game;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join as ExprJoin;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function add(Game $game, bool $flush = true): void {
        $this->_em->persist($game);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Game $game, bool $flush = true): void {
        $this->_em->remove($game);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function gameByPublishedAt() {
       
        return $this->createQueryBuilder('g')
            ->select('g')
            ->orderBy('g.publishedAt','DESC')
            ->setMaxResults(9)
            ->getQuery()->getResult();
    }

    public function mostPlayedGame(int $limit) {
       
        return $this->createQueryBuilder('g')
            ->join(Library::class, 'lib', ExprJoin::WITH, 'lib.game = g')
            ->groupBy('g.name')
            ->orderBy('SUM(lib.gameTime)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function mostBoughtGame(int $limit) {
       
        return $this->createQueryBuilder('g')
            ->join(Library::class, 'lib', ExprJoin::WITH, 'lib.game = g')
            ->groupBy('g.name')
            ->orderBy('COUNT(lib.game)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getGameBySlug(string $slug) {
       
        return $this->createQueryBuilder('g')
            ->join('g.genres', 'gen')
            ->join('g.countries', 'c')
            ->join('g.comments', 'comment')
            ->where('g.slug = :slug')
            ->setParameter('slug',$slug)
            ->orderBy('comment.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

  
}
