<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-25
 * Time: 20:01
 */

namespace App\Doctrine;


use App\Entity\Member;
use App\Entity\Schedule;
use App\Entity\StandUpConfig;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class StandUpConfigEntityListener
{
    private $security;

    private $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @param StandUpConfig $object
     */
    public function prePersist($object)
    {
        if (!$object->getSchedule()) {
            $schedule = new Schedule();
            $object->setSchedule($schedule);
        }

        if ($object->getAuthor())
            return;

        /** @var User $user */
        if($user = $this->security->getUser()) {
            $object->setAuthor($user);

            if (!$object->getSpace() && $user->getSpace())
                $object->setSpace($user->getSpace());

            $member = new Member();
            $member->setUser($user)
                ->setCanWrite(true)
                ->setCanRead(true)
                ->setCanEdit(true)
                ->setConfig($object);
        }
    }
}