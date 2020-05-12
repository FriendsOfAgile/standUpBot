<?php

namespace App\Repository;

use App\Entity\ChatState;
use App\Entity\StandUpConfig;
use App\Entity\User;
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

    /**
     * @param User $user
     * @param StandUpConfig $config
     * @return ChatState|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCurrentState(User $user, StandUpConfig $config): ?ChatState
    {
        $today = new \DateTime();

        $query = $this->createQueryBuilder('s');
        return $query
            ->where('s.user = :user')
            ->andWhere('s.config = :config')
            ->andWhere($query->expr()->between('s.timestamp', ':dateFrom', ':dateTo'))
            ->setParameters(array(
                'user' => $user,
                'config' => $config,
                'dateFrom' => $today->format('Y-m-d 00:00:00'),
                'dateTo' => $today->format('Y-m-d 23:59:59')
            ))->getQuery()
            ->getOneOrNullResult()
        ;
    }


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
