<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-05-08
 * Time: 19:48
 */

namespace App\Tests\Functional;


use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class StandUpConfigResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    /**
     * @test
     */
    public function user_cant_see_other_configs()
    {
        $user1 = $this->createUser('user1@test.com', 'slack', 'user1test');
        $user2 = $this->createUser('user2@test.com', 'slack', 'user2test');

        $config1 = $this->createConfig($user1, 'Config 1');
        $config2 = $this->createConfig($user2, 'Config 2');

        $this->logIn($user1, 'user1test');
        $response = $this->client->request('GET', '/api/configs');
        $result1 = $response->toArray();

        $this->logIn($user2, 'user2test');
        $response = $this->client->request('GET', '/api/configs');
        $result2 = $response->toArray();

        $this->assertEquals(1, $result1['hydra:totalItems']);
        $this->assertEquals(1, $result2['hydra:totalItems']);

        $this->assertEquals($config1->getName(), current($result1['hydra:member'])['name']);
        $this->assertEquals($config2->getName(), current($result2['hydra:member'])['name']);
    }

    /**
     * @test
     */
    public function user_can_see_space_configs()
    {
        $em = $this->getEntityManager();

        $user1 = $this->createUser('user1@test.com', 'slack', 'user1test');
        $user2 = $this->createUser('user2@test.com', 'slack', 'user2test');

        $space = $user1->getSpace();
        $user2->setSpace($space);

        $em->persist($user2);
        $em->flush();

        $config = $this->createConfig($user1, 'Test Config');

        $this->logIn($user2, 'user2test');

        $response = $this->client->request('GET', '/api/configs');
        $result = $response->toArray();

        $this->assertEquals(1, $result['hydra:totalItems']);
        $this->assertEquals($config->getName(), current($result['hydra:member'])['name']);
    }
}