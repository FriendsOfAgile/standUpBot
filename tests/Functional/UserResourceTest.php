<?php

namespace App\Tests\Functional;

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
}
