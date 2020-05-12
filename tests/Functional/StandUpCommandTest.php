<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-05-12
 * Time: 20:54
 */

namespace App\Tests\Functional;


use App\Entity\ChatState;
use App\Entity\Question;
use App\Repository\ChatStateRepository;
use App\Service\StandUpService;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class StandUpCommandTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    /** @var StandUpService */
    private $service;

    /**
     * @test
     */
    public function does_not_create_additional_state()
    {
        $user = $this->createUser('user1@test.com', 'slack', 'user1test');
        $config = $this->createConfig($user, 'Default');
        $em = $this->getEntityManager();

        $question = new Question();
        $question->setSort(0)
            ->setText('PHPUnit text');

        $config->addQuestion($question);

        /** @var ChatStateRepository $repository */
        $repository = $em->getRepository(ChatState::class);

        $list = $repository->findBy(['user' => $user, 'config' => $config]);
        $this->assertCount(0, $list);

        $this->service->startStandUp($user, $config);

        $list = $repository->findBy(['user' => $user, 'config' => $config]);
        $this->assertCount(1, $list);

        $this->service->startStandUp($user, $config);

        $list = $repository->findBy(['user' => $user, 'config' => $config]);
        $this->assertCount(1, $list);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->service = self::$container->get(StandUpService::class);
    }

    public function tearDown(): void
    {
        unset($this->service);
    }
}