<?php

namespace App\Repository;

use App\Entity\DirectMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DirectMessage>
 *
 * @method DirectMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DirectMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DirectMessage[]    findAll()
 * @method DirectMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DirectMessage::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(DirectMessage $entity, bool $flush = true): void
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
    public function remove(DirectMessage $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getAllDirectMessages($id){
        return $this->createQueryBuilder('dm')
        ->join('dm.createdBy', 'a')
        ->where('a.id = :id OR dm.receiver = :id')
        ->orderBy('dm.createdAt', 'DESC')
        ->groupBy('dm.receiver')
        ->setParameter('id', $id)
        ->setMaxResults(1)
        ->getQuery()->getResult();
    }


    public function getDirectMessageByUser($idUser, $idReceiver){
        return $this->createQueryBuilder('dm')
        ->join('dm.createdBy', 'a')
        ->where('a.id = :id OR dm.receiver = :id')
        ->andWhere('dm.receiver = :idReceiver')
        ->setParameter('id', $idUser)
        ->setparameter('idReceiver', $idReceiver)
        ->getQuery()->getResult();
    }

    
    // /**
    //  * @return DirectMessage[] Returns an array of DirectMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DirectMessage
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
