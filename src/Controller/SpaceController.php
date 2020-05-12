<?php

namespace App\Controller;

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
        try {
            $list = $service->getUsers();
            return $this->json($list);
        } catch (\Exception $e) {}

        return $this->json(['status' => 'error'], 500);
    }

    /**
     * @Route("/api/channels", methods={"GET"})
     */
    public function channels(SlackService $service)
    {
        /** @var User $user */
        $user = $this->getUser();
        $service->setAccessToken($user->getSpace()->getToken());
        try {
            $list = $service->listChannels();
            return $this->json($list);
        } catch (\Exception $e) {}
        return $this->json(['status' => 'error'], 500);
    }
}
