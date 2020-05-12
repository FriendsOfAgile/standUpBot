<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-26
 * Time: 19:03
 */

namespace App\Service;


use App\Entity\Space;
use App\Entity\StandUpConfig;
use App\Entity\StandUpDelay;
use App\Entity\User;
use App\Repository\StandUpDelayRepository;
use App\Repository\UserRepository;
use App\Traits\LoggerTrait;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleService
{
    use LoggerTrait;

    /** @var StandUpConfig */
    private $config;

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setConfig(StandUpConfig $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig(): ?StandUpConfig
    {
        return $this->config;
    }

    /**
     * @return User[]
     * @throws \Exception
     */
    public function getUsersToStandUp(): array
    {
        $result = array();
        if ($this->isDayToStandUp() && $this->isTimeToStandUp()) {
            $members = $this->getConfig()->getMembers();
            foreach ($members as $member) {
                if (!$member->getUser() || !$member->getCanWrite())
                    continue;
                if (!$this->isUserDelayed($member->getUser()) && !$this->userHasStandUpToday($member->getUser()))
                    $result[] = $member->getUser();

            }
        }
        return $result;
    }

    public function userHasStandUpToday(User $user): bool
    {
        if ($user->getStandUps()->count() <= 0)
            return false;

        /** @var UserRepository $repository */
        $repository = $this->em->getRepository(User::class);
        $queryBuilder = $repository->createQueryBuilder('u')
            ->leftJoin('u.standUps', 's')
            ->where('u.id = :userId')
            ->andWhere('s.config = :configId');
        $queryBuilder->andWhere($queryBuilder->expr()->between('s.timestamp', ':date_from', ':date_to'));
        $queryBuilder->setParameter('userId', $user->getId());
        $queryBuilder->setParameter('date_from', (new \DateTime())->format('Y-m-d 00:00:00'));
        $queryBuilder->setParameter('date_to', (new \DateTime())->format('Y-m-d 23:59:59'));
        $queryBuilder->setParameter('configId', $this->config->getId());

        $result = $queryBuilder->getQuery()->getOneOrNullResult();
        return (bool)$result;
    }

    public function isUserDelayed(User $user): bool
    {
        if ($this->isUserGotTooManyRequests($user))
            return true;

        /** @var StandUpDelayRepository $repository */
        $repository = $this->em->getRepository(StandUpDelay::class);
        $queryBuilder = $repository->createQueryBuilder('d');

        $now = new \DateTime();

        $queryBuilder->where($queryBuilder->expr()->between('d.sendAfter', ':date_from', ':date_to'))
            ->andWhere('d.user = :user')
            ->andWhere('d.config = :config')
            ->andWhere('d.sendAfter > :after')
            ->setParameters(array(
                'user' => $user,
                'date_from' =>  (new \DateTime())->format('Y-m-d 00:00:00'),
                'date_to' => (new \DateTime())->format('Y-m-d 23:59:59'),
                'after' => $now->format('Y-m-d H:i:s'),
                'config' => $this->config
            ));


        $r = $queryBuilder->getQuery()->getResult();

        return (bool)$r;
    }

    public function isUserGotTooManyRequests(User $user): bool
    {
        /** @var StandUpDelayRepository $repository */
        $repository = $this->em->getRepository(StandUpDelay::class);
        $queryBuilder = $repository->createQueryBuilder('d');

        $now = new \DateTime();

        $queryBuilder->select('count(d.id)')
            ->where($queryBuilder->expr()->between('d.sendAfter', ':date_from', ':date_to'))
            ->andWhere('d.user = :user')
            ->andWhere('d.config = :config')
            ->setParameters(array(
                'user' => $user,
                'date_from' =>  ($now)->format('Y-m-d 00:00:00'),
                'date_to' => ($now)->format('Y-m-d 23:59:59'),
                'config' => $this->config
            ));


        $r = $queryBuilder->getQuery()->getSingleScalarResult();

        return !$r || $r <= 3;
    }

    /**
     * Checks if it is day to standUp by current config
     * @return bool
     * @throws \Exception
     */
    public function isDayToStandUp(): bool
    {
        if (!$this->getConfig() || !$this->getConfig()->getSchedule()) {
            $this->logInfo('There is no schedule');
            return false;
        }
        $currentDay = strtolower((new \DateTime())->format('l'));
        return $this->getConfig()->getSchedule()->getWeekSchedule()[$currentDay] ?? false;
    }

    /**
     * Checks if it is time to standUp by current config
     * @return bool
     * @throws \Exception
     */
    public function isTimeToStandUp(): bool
    {
        if (!$this->getConfig() || !$this->getConfig()->getSchedule()) {
            $this->logInfo('There is no schedule');
            return false;
        }

        $targetTime = $this->getConfig()->getSchedule()->getTime();
        $now = (new \DateTime())->format('H:i');

        $targetArray = explode(':', $targetTime);
        $nowArray = explode(':', $now);

        $targetMinutes = intval($targetArray[0]) * 60 + intval($targetArray[1]);
        $nowMinutes = intval($nowArray[0]) * 60 + intval($nowArray[1]);

        return $nowMinutes >= $targetMinutes;
    }
}