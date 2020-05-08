<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-26
 * Time: 18:05
 */

namespace App\Controller;


use App\Entity\StandUpConfig;
use App\Repository\StandUpConfigRepository;
use App\Service\ScheduleService;
use App\Service\SlackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    /**
     * @Route("/test")
     * @param StandUpConfigRepository $repository
     * @param ScheduleService $service
     */
    public function test(StandUpConfigRepository $repository, ScheduleService $service, SlackService $slack)
    {
        $list = $repository->findAll();
        /** @var StandUpConfig $config */
        $config = end($list);

        $config->getSchedule()->setWeekSchedule(array(
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => true,
            'saturday' => true,
            'sunday' => true
        ));

        $service->setConfig($config);
//        dump($config->getId());
//        dump($service->isDayToStandUp());
//        dump($service->isTimeToStandUp());
//        dump($service->getUsersToStandUp());

        $me = current($service->getUsersToStandUp());
        dump($me);

        $slack->postMessage($me, 'Hey');
        die;
    }
}