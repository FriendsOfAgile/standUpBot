<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-25
 * Time: 20:13
 */

namespace App\ApiPlatform;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class UserCollectionExtension implements QueryCollectionExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        if ($resourceClass !== User::class)
            return;

        /** @var User $user */
        if ($user = $this->security->getUser()) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder->where(sprintf('%s.id = :userId', $rootAlias))
                ->setParameter('userId', $user->getId());
        }
    }

}