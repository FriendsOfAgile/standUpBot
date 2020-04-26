<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-26
 * Time: 18:05
 */

namespace App\Controller;


use App\Entity\StandUpConfig;
use App\Service\SlackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    /**
     * @Route("/api/configs/{config}/members", methods={"GET"})
     */
    public function members(StandUpConfig $config, SlackService $service)
    {
        $service->setAccessToken($config->getSpace()->getToken());
        $list = $service->getUsers();
        return $this->json($list);
    }
}