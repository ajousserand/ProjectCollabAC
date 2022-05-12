<?php

namespace App\Repository;

use App\Entity\Forum;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forum>
 *
 * @method Forum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forum[]    findAll()
 * @method Forum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Forum $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Forum $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getForum($name){
        return $this->createQueryBuilder('f')
        ->where('f.title = :name')
        ->setParameter('name', $name)
        ->leftjoin('f.topics', 't')
        ->getQuery()
        ->getSingleResult();
    }

    public function getTopForumMessage(){
        $date = new DateTime();
        $date2 = clone $date;
        $date2->modify('-7 days');
        return $this->createQueryBuilder('f')
        ->addSelect('COUNT(m) as counted')
        ->innerJoin('f.topics', 't')
        ->innerJoin('t.messages', 'm')
        ->where('m.createdAt >= :date2')
        ->setParameter('date2', $date2)
        ->orderBy('counted', 'DESC')
        ->groupBy('f.id')
        ->setMaxResults(1)
        ->getQuery()->getOneOrNullResult();
    }

    public function getGoldForum(){
        return $this->createQueryBuilder('f')
        ->addSelect('COUNT(m) as counted')
        ->innerJoin('f.topics', 't')
        ->innerJoin('t.messages', 'm')
        ->orderBy('counted', 'DESC')
        ->groupBy('f.id')
        ->setMaxResults(1)
        ->getQuery()->getOneOrNullResult();
    }

    public function getNAForum(){
        return $this->createQueryBuilder('f')
        ->addSelect('COUNT(m) as counted')
        ->innerJoin('f.topics', 't')
        ->innerJoin('t.messages', 'm')
        ->orderBy('counted', 'ASC')
        ->groupBy('f.id')
        ->setMaxResults(1)
        ->getQuery()->getSingleResult();
    }

    


    // /**
    //  * @return Forum[] Returns an array of Forum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Forum
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
