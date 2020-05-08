<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\ChatState;
use App\Entity\StandUp;
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

                    /** @var ChatState $state */
                    $state = $this->getDoctrine()->getRepository(ChatState::class)->findOneBy([
                        'user' => $user
                    ]);

                    $logger->info('State debug', (array)$state);

                    $answers = $state->getAnswers();
                    $questions = $state->getQuestions();

                    if (!empty($questions)) {
                        $question = array_shift($questions);
                        $question['answer'] = $text;
                        $answers[] = $question;

                        $state->setQuestions($questions)
                            ->setAnswers($answers);

                        $manager = $this->getDoctrine()->getManager();
                        $manager->persist($state);

                        if (empty($questions)) {
                            if ($last = $state->getConfig()->getMessageAfter())
                                $slack->postMessage($user, $last);

                            $standUp = new StandUp();
                            $standUp->setUser($user)
                                ->setConfig($state->getConfig());
                            $manager->persist($standUp);
                            foreach($state->getAnswers() as $sort => $answer) {
                                $a = new Answer();
                                $a->setAnswer($answer['answer'])
                                    ->setQuestion($answer['text'])
                                    ->setColor($answer['color'])
                                    ->setSort($sort);
                                $standUp->addAnswer($a);
                                $manager->persist($a);
                            }
                            $manager->remove($state);
                        } else {
                            $question = current($questions);
                            $slack->postMessage($user, $question['text']);
                        }

                        $manager->flush();
                    }

                    break;
            }
        }

        return $this->json(['status' => 'ok']);
    }
}
