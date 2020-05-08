<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-05-08
 * Time: 21:10
 */

namespace App\Service;


use App\Entity\Answer;
use App\Entity\ChatState;
use App\Entity\StandUp;
use App\Entity\StandUpConfig;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class StandUpService
{
    /**
     * @var SlackService
     */
    private $slack;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SlackService $slack, EntityManagerInterface $em)
    {
        $this->slack = $slack;
        $this->em = $em;
    }

    /**
     * Begins standUp and creates all required states
     *
     * @param User $user
     * @param StandUpConfig $config
     * @return bool
     */
    public function startStandUp(User $user, StandUpConfig $config): bool
    {
        try {
            if ($config->getQuestions()->count() <= 0) {
                return false;
            }

            if ($welcome = $config->getMessageBefore()) {
                $this->slack->postMessage($user, $welcome);
            }

            $state = new ChatState();

            $questions = array();
            foreach ($config->getQuestions() as $question) {
                $questions[] = array(
                    'color' => $question->getColor(),
                    'text' => $question->getText()
                );
            }

            $state->setQuestions($questions)
                ->setConfig($config)
                ->setUser($user);

            $currentQuestion = current($questions);
            $this->slack->postMessage($user, $currentQuestion['text']);

            $this->em->persist($state);
            $this->em->flush();

            return true;
        } catch (\Exception $e) {

        }
        return false;
    }

    /**
     * Retrieves next question or "Thank you" message
     *
     * @param User $user
     * @param string $text
     * @return bool
     */
    public function processStandUp(User $user, string $text): bool
    {
        try {
            /** @var ChatState $state */
            $state = $this->em->getRepository(ChatState::class)->findOneBy([
                'user' => $user
            ]);

            if (!$state) {
                throw new \Exception('Unexpected message');
            }

            $answers = $state->getAnswers();
            $questions = $state->getQuestions();

            $question = array_shift($questions);
            $question['answer'] = $text;
            $answers[] = $question;

            $state->setQuestions($questions)
                ->setAnswers($answers);

            $this->em->persist($state);

            if (empty($questions)) {
                $this->finishStandUp($user, $state);
            } else {
                $question = current($questions);
                $this->slack->postMessage($user, $question['text']);
            }

            $this->em->flush();
            return true;
        } catch (\Exception $e) {}
        return false;
    }

    public function finishStandUp(User $user, ChatState $state)
    {
        if ($last = $state->getConfig()->getMessageAfter())
            $this->slack->postMessage($user, $last);

        $standUp = new StandUp();
        $standUp->setUser($user)
            ->setConfig($state->getConfig());
        $this->em->persist($standUp);
        foreach($state->getAnswers() as $sort => $answer) {
            $a = new Answer();
            $a->setAnswer($answer['answer'])
                ->setQuestion($answer['text'])
                ->setColor($answer['color'])
                ->setSort($sort);
            $standUp->addAnswer($a);
            $this->em->persist($a);
        }
        $this->em->remove($state);
        $this->em->flush();
    }
}