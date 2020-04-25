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
    public function can_get_users_as_signed()
    {
        $this->createUserAndLogIn('example@domain.com', 'foo');

        $this->client->request('GET', '/api/users', [
            'json' => []
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

}
