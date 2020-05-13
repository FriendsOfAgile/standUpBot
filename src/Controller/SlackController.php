<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UnexpectedAnswerException;
use App\Service\SlackService;
use App\Service\StandUpService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SlackController extends AbstractController
{
    /**
     * @Route("/channels/slack/event", name="slack")
     */
    public function event(Request $request, StandUpService $service, LoggerInterface $logger, SlackService $slack)
    {
        $data = json_decode($request->getContent(), true);
        $action = $data['event']['type'] ?? ($data['type'] ?? null);
        
        if (!isset($data['event']['bot_profile'])) {
            switch ($action) {
                case 'url_verification':
                    return $this->json([$data['challenge']]);
                    break;
                case 'message':
                    if (!isset($data['event']['user']))
                        break;
                    $data = $data['event'];
                    $text = $data['text'];

                    /** @var User $user */
                    $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                        'uid' => $data['user']
                    ]);

                    try {
                        $service->processStandUp($user, $text);
                    } catch (UnexpectedAnswerException $e) {
                        $slack->postMessage($user, 'Sorry, it is not the time for stand up.');
                    } catch (\Exception $e) {
                        $logger->emergency('Exception on new event', ['message' => $e->getMessage(), 'request' => $data]);
                    }
                    break;
                default:
                    $logger->alert('Unknown event type', $data);
                    break;
            }
        }

        return $this->json(['status' => 'ok']);
    }
}
