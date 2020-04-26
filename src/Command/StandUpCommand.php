<?php

namespace App\Command;

use App\Entity\Space;
use App\Service\ScheduleService;
use App\Service\SlackService;
use App\Traits\LoggerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StandUpCommand extends Command
{
    use LoggerTrait;

    protected static $defaultName = 'app:stand-up';

    private $service;

    private $em;
    /**
     * @var SlackService
     */
    private $slack;

    public function __construct(?string $name = null, ScheduleService $service, EntityManagerInterface $em, SlackService $slack)
    {
        parent::__construct($name);
        $this->service = $service;
        $this->em = $em;
        $this->slack = $slack;
    }

    protected function configure()
    {
        $this
            ->setDescription('Runs main job to send stand-up requests')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $spaces = $this->em->getRepository(Space::class)->findAll();
        $io->text(sprintf('Current time is %s', (new \DateTime())->format('Y-m-d H:i:s')));
        /** @var Space $space */
        $this->slack->setAccessToken($_ENV['SLACK_BOT_TOKEN']);
        foreach ($spaces as $space) {
            $configs = $space->getStandUpConfigs();
            $io->text(sprintf('Checking %s space. Found %d configs', $space->getName(), $configs->count()));
            foreach ($configs as $config) {
                $this->service->setConfig($config);
                $users = $this->service->getUsersToStandUp();
                if (count($users)) {
                    $io->text(sprintf('Found %d users to stand-up', count($users)));
                    foreach ($users as $user) {
                        if ($io->ask(sprintf('Do you want to send message to %s', $user->getName()), 'yes') == 'yes') {
                            if ($config->getMessageBefore())
                                $this->slack->postMessage($user, $config->getMessageBefore());

                        }
                        //Отправляем welcome
                        //Отправляем первый вопрос
                        //Заносим в Delay-лист
                    }
                }
            }
        }

        return 0;
    }
}
