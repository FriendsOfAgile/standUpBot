<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-25
 * Time: 20:01
 */

namespace App\Doctrine;


use App\Entity\StandUpConfig;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class StandUpConfigEntityListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param StandUpConfig $object
     */
    public function prePersist($object)
    {
        if ($object->getAuthor())
            return;

        /** @var User $user */
        if($user = $this->security->getUser()) {
            $object->setAuthor($user);

            if (!$object->getSpace() && $user->getSpace())
                $object->setSpace($user->getSpace());
        }
    }
}