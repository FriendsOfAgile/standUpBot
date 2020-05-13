<?php

namespace App\Command;

use App\Entity\Space;
use App\Service\ScheduleService;
use App\Service\StandUpService;
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
     * @var StandUpService
     */
    private $standUp;

    public function __construct(?string $name = null, ScheduleService $service, EntityManagerInterface $em, StandUpService $standUp)
    {
        parent::__construct($name);
        $this->service = $service;
        $this->em = $em;
        $this->standUp = $standUp;
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
        $io->text(sprintf('Current time is %s', (new \DateTime())->format('Y-m-d H:i:s')));

        $spaces = $this->em->getRepository(Space::class)->findAll();

        /** @var Space $space */
        foreach ($spaces as $space) {

            $configs = $space->getStandUpConfigs();
            $io->text(sprintf('Checking %s space. Found %d configs', $space->getName(), $configs->count()));
            foreach ($configs as $config) {
                $this->service->setConfig($config);
                $users = $this->service->getUsersToStandUp();
                if (!empty($users)) {

                    $io->text(sprintf('Found %d users to stand-up', count($users)));

                    foreach ($users as $user) {
                        if ($io->confirm(sprintf('Do you want to send message to %s', $user->getName()))) {
                            $this->standUp->startStandUp($user, $config);
                        }
                    }
                }
            }
        }

        return 0;
    }
}
