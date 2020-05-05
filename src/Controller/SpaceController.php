<?php

namespace App\Controller;

use App\Entity\Space;
use App\Service\SlackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SpaceController extends AbstractController
{
    /**
     * @Route("/api/members", methods={"GET"})
     */
    public function members(Space $space, SlackService $service)
    {
        $service->setAccessToken($space->getToken());
        $list = $service->getUsers();
        return $this->json($list);
    }
}
