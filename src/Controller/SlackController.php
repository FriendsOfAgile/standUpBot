<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\ChatState;
use App\Entity\User;
use App\Service\SlackService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SlackController extends AbstractController
{
    /**
     * @Route("/channels/slack/event", name="slack")
     */
    public function event(Request $request, LoggerInterface $logger, SlackService $slack)
    {
        $data = json_decode($request->getContent(), true);
        $logger->info('Slack event', $data);

        $action = $data['event']['type'] ?? ($data['type'] ?? null);

        switch ($action) {
            case 'url_verification':
                return $this->json([$data['challenge']]);
                break;
            default:
                if (!isset($data['user']))
                    break;
                $data = $data['event'];
                $logger->info('Got event', $data);
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
                    'uid' => $data['user']
                ]);
                /** @var ChatState $state */
                $state = $this->getDoctrine()->getRepository(ChatState::class)->findOneBy([
                    'user' => $user
                ]);
                $logger->info('State debug', (array)$state);

                $answer = new Answer();
                $answer->setQuestion($state->getQuestion())
                    ->setAnswer($data['text']);

                if ($question = $state->getNextQuestion()) {
                    $slack->postMessage($user, $question->getText());
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->remove($state);
                $manager->persist($answer);
                $manager->flush();
                break;
        }
        return $this->json(['status' => 'ok']);
    }
}
