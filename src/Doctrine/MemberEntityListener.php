<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-25
 * Time: 20:01
 */

namespace App\Doctrine;


use App\Entity\Member;
use App\Entity\User;
use App\Service\SlackService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class MemberEntityListener
{
    private $security;

    private $service;

    private $em;


    public function __construct(Security $security, SlackService $service, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->service = $service;
        $this->em = $em;
    }

    /**
     * @param Member $object
     */
    public function prePersist($object)
    {
        if (!$object->getUser() && $object->getUid() && $object->getConfig()) {
            if ($existedUser = $this->em->getRepository(User::class)->findOneBy([
                'uid' => $object->getUid(),
                'space' => $object->getConfig()->getSpace()
            ])) {
                /** @var User $existedUser */
                $object->setUser($existedUser);
            } else {
                $userData = $this->service->getUser($object->getUid());
                $user = new User();
                $user->setSpace($object->getConfig()->getSpace())
                    ->setTimeZone($userData['timeZone'])
                    ->setAvatar($userData['avatar'])
                    ->setEmail($userData['email'])
                    ->setUid($object->getUid())
                    ->setName($userData['name']);
                $this->em->persist($user);
                $this->em->flush();
                $object->setUser($user);
            }
        }
    }
}