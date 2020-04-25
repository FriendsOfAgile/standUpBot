<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    /**
     * @test
     */
    public function cant_get_users_as_anonymous()
    {
        $this->client->request('GET', '/api/users', [
            'json' => []
        ]);

        $this->assertResponseStatusCodeSame(307);
    }

    /**
     * @test
     */
    public function can_get_only_current_user()
    {
        for ($i = 0; $i < 3; $i++) {
            $this->createUser(sprintf('example%d@domain.com', $i));
        }

        $this->createUserAndLogIn('example@domain.com', 'foo');

        $response = $this->client->request('GET', '/api/users', [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(200);

        $response = json_decode($response->getContent(true), true);
        $this->assertCount(1, $response['hydra:member']);
    }

    /**
     * @test
     */
    public function cant_access_other_users_directly()
    {
        $user1 = $this->createUser('user1@domain.com', 'user', 'foo');
        $user2 = $this->createUser('user2@domain.com', 'user', 'foo');

        $this->logIn($user1, 'foo');

        $user1Iri = $this->findIriBy(User::class, ['id' => $user1->getId()]);
        $user1Ir2 = $this->findIriBy(User::class, ['id' => $user2->getId()]);


        $this->client->request('GET', $user1Iri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', $user1Ir2, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(403);
    }
}
