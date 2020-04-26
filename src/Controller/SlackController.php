<?php

namespace App\Controller;

use App\Entity\ChatState;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SlackController extends AbstractController
{
    /**
     * @Route("/channels/slack/event", name="slack")
     */
    public function event(Request $request, LoggerInterface $logger)
    {
        $data = json_decode($request->getContent(), true);
        $logger->info('Slack event', $data);

        $action = $data['event']['type'] ?? ($data['type'] ?? null);

        switch ($action) {
            case 'url_verification':
                return $this->json([$data['challenge']]);
                break;
            default:
                $data = $data['event'];
                $logger->info('Got event', $data);
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                    'uid' => $data['user']
                ]);
                $state = $this->getDoctrine()->getRepository(ChatState::class)->findOneBy([
                    'user' => $user
                ]);

                $logger->info('State debug', (array)$state);
                break;
        }
        return $this->json(['status' => 'ok']);
    }
}
