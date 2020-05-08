<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\StandUpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SlackController extends AbstractController
{
    /**
     * @Route("/channels/slack/event", name="slack")
     */
    public function event(Request $request, StandUpService $service)
    {
        $data = json_decode($request->getContent(), true);
        $action = $data['event']['type'] ?? ($data['type'] ?? null);

        if (!isset($data['event']['bot_profile'])) {
            switch ($action) {
                case 'url_verification':
                    return $this->json([$data['challenge']]);
                    break;
                case 'message':
                default:
                    if (!isset($data['event']['user']))
                        break;
                    $data = $data['event'];
                    $text = $data['text'];

                    /** @var User $user */
                    $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                        'uid' => $data['user']
                    ]);

                    $service->processStandUp($user, $text);
                    break;
            }
        }

        return $this->json(['status' => 'ok']);
    }
}
