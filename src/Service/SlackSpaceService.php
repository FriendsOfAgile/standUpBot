<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-21
 * Time: 23:59
 */

namespace App\Service;


use App\Entity\Space;
use App\Service\Interfaces\SpaceServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class SlackSpaceService implements SpaceServiceInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SlackService */
    private $slack;

    public function __construct(SlackService $slack)
    {
        $this->slack = $slack;
    }

    /**
     * @required
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function pullSpaceData(string $spaceId, string $token): ?Space
    {
        if ($space = $this->findExistingSpace($spaceId))
            return $space;

        $this->slack->setAccessToken($token);
        if (!$data = $this->slack->getTeamInfo())
            return null;

        $team = &$data['team'];

        $space = new Space();
        $space->setAvatar($team['icon']['image_68'])
            ->setName($team['name'])
            ->setUid($spaceId)
            ->setType('slack')
            ->setToken($token);

        return $space;
    }

    /**
     * {@inheritdoc}
     */
    public function findExistingSpace(string $spaceId): ?Space
    {
        return $this->em->getRepository(Space::class)
            ->findOneBy(['type' => 'slack', 'uid' => $spaceId]);
    }

}