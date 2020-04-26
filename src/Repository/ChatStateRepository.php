<?php

namespace App\Repository;

use App\Entity\ChatState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChatState|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatState|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatState[]    findAll()
 * @method ChatState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatState::class);
    }

    // /**
    //  * @return ChatState[] Returns an array of ChatState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChatState
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
