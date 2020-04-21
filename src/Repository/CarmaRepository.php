<?php

namespace App\Repository;

use App\Entity\Carma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Carma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carma[]    findAll()
 * @method Carma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarmaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carma::class);
    }

    // /**
    //  * @return Carma[] Returns an array of Carma objects
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
    public function findOneBySomeField($value): ?Carma
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
