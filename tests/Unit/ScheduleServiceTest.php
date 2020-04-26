<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-26
 * Time: 19:07
 */

namespace App\Tests\Unit;


use App\Entity\Schedule;
use App\Entity\Space;
use App\Entity\StandUpConfig;
use App\Entity\User;
use App\Service\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ScheduleServiceTest extends KernelTestCase
{
    /** @var ScheduleService */
    private $service;

    /**
     * @dataProvider trueFalseProvider
     * @param $isEnabled
     */
    public function testIsDayToStandUp($isEnabled)
    {
        $space = new Space();
        $space->setName('Unit Test')
            ->setType('slack');

        $user = new User();
        $user->setSpace($space)
            ->setName('Unit User')
            ->setEmail('example@domain.com')
            ->setUid(md5(rand(1,100)));

        $schedule = new Schedule();
        $schedule->setTime('12:00')
            ->setWeekSchedule(array(
                'monday' => $isEnabled,
                'tuesday' => $isEnabled,
                'wednesday' => $isEnabled,
                'thursday' => $isEnabled,
                'friday' => $isEnabled,
                'saturday' => $isEnabled,
                'sunday' => $isEnabled
            ));

        $config = new StandUpConfig();
        $config->setName('Unit Config')
            ->setSpace($space)
            ->setSchedule($schedule)
            ->setAuthor($user)
            ->setMessageAfter('bye')
            ->setMessageBefore('hey');

        $result = $this->service->setConfig($config)->isDayToStandUp();

        $this->assertSame($isEnabled, $result);
    }

    /**
     * @dataProvider timeProvider
     */
    public function testIsTimeToStandUp(\DateTime $dateTime, $expected)
    {
        $space = new Space();
        $space->setName('Unit Test')
            ->setType('slack');

        $user = new User();
        $user->setSpace($space)
            ->setName('Unit User')
            ->setEmail('example@domain.com')
            ->setUid(md5(rand(1,100)));

        $schedule = new Schedule();
        $schedule->setTime($dateTime->format('H:i'))
            ->setWeekSchedule(array(
                'monday' => true,
                'tuesday' => true,
                'wednesday' => true,
                'thursday' => true,
                'friday' => true,
                'saturday' => true,
                'sunday' => true
            ));

        $config = new StandUpConfig();
        $config->setName('Unit Config')
            ->setSpace($space)
            ->setSchedule($schedule)
            ->setAuthor($user)
            ->setMessageAfter('bye')
            ->setMessageBefore('hey');

        $result = $this->service->setConfig($config)->isTimeToStandUp();

        $this->assertSame($expected, $result);
    }

    public function trueFalseProvider(): array
    {
        return array(
            'true' => array(true),
            'false' => array(false)
        );
    }

    public function timeProvider(): array
    {
        $now = new \DateTime();
        $before = clone $now;
        $before->modify('-3 hours');

        $after = clone $now;
        $after->modify('+3 hours');

        return array(
            'before' => array($before, true),
            'after' => array($after, false)
        );
    }

    public function setUp(): void
    {
        self::bootKernel();
        $em = self::$container->get('doctrine')->getManager();
        $this->service = new ScheduleService($em);
    }

    public function tearDown(): void
    {
        unset($this->service);
    }
}