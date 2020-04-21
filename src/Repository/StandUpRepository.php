<?php

namespace App\Repository;

use App\Entity\StandUp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StandUp|null find($id, $lockMode = null, $lockVersion = null)
 * @method StandUp|null findOneBy(array $criteria, array $orderBy = null)
 * @method StandUp[]    findAll()
 * @method StandUp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandUpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StandUp::class);
    }

    // /**
    //  * @return StandUp[] Returns an array of StandUp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StandUp
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
