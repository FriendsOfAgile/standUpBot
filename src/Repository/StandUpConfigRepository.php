<?php

namespace App\Repository;

use App\Entity\StandUpConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StandUpConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method StandUpConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method StandUpConfig[]    findAll()
 * @method StandUpConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandUpConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StandUpConfig::class);
    }

    // /**
    //  * @return StandUpConfig[] Returns an array of StandUpConfig objects
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
    public function findOneBySomeField($value): ?StandUpConfig
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
