<?php

namespace App\Controller;

use App\Entity\Space;
use App\Entity\User;
use App\Service\SlackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SpaceController extends AbstractController
{
    /**
     * @Route("/api/members", methods={"GET"})
     */
    public function members(SlackService $service)
    {
        /** @var User $user */
        $user = $this->getUser();
        $service->setAccessToken($user->getSpace()->getToken());
        $list = $service->getUsers();
        return $this->json($list);
    }
}
