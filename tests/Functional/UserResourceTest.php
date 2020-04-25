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

        $this->createUserAndLogIn('example@domain.com', 'foo', 'user');

         $this->client->request('GET', '/api/users', [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains(['hydra:totalItems' => 1]);
    }

    /**
     * @test
     */
    public function can_get_current_user_directly()
    {
        $user = $this->createUserAndLogIn('user@domain.com', 'foo', 'user');
        $userIri = $this->findIriBy(User::class, ['id' => $user->getId()]);
        $this->client->request('GET', $userIri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @test
     */
    public function can_edit_current_user_directly()
    {
        $user = $this->createUserAndLogIn('user@domain.com', 'foo', 'user');
        $userIri = $this->findIriBy(User::class, ['id' => $user->getId()]);

        $this->client->request('PUT', $userIri, [
            'json' => [
                'name' => 'updated'
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @test
     */
    public function cant_get_other_users_directly()
    {
        $this->createUserAndLogIn('user1@domain.com', 'foo', 'user');
        $user = $this->createUser('user2@domain.com', 'user', 'foo');

        $userIri = $this->findIriBy(User::class, ['id' => $user->getId()]);


        $this->client->request('GET', $userIri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * @test
     */
    public function cant_edit_other_users_directly()
    {
        $this->createUserAndLogIn('user1@domain.com', 'foo', 'user');
        $user = $this->createUser('user2@domain.com', 'user', 'foo');

        $userIri = $this->findIriBy(User::class, ['id' => $user->getId()]);


        $this->client->request('PUT', $userIri, [
            'json' => [
                'name' => 'updated'
            ]
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * @test
     */
    public function cant_delete_users()
    {
        $current = $this->createUserAndLogIn('user@domain.com', 'foo', 'user');
        $user = $this->createUser('user@domain.com', 'user', 'foo');

        $userIri = $this->findIriBy(User::class, ['id' => $user->getId()]);

        $this->client->request('DELETE', $userIri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(403);

        $userIri = $this->findIriBy(User::class, ['id' => $current->getId()]);

        $this->client->request('DELETE', $userIri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * @test
     */
    public function admin_can_get_other_users_directly()
    {
        $user = $this->createUser('user@domain.com', 'user', 'foo');
        $admin = $this->createUser('admin@domain.com', 'admin', 'foo');

        $userIri = $this->findIriBy(User::class, ['id' => $user->getId()]);

        $this->logIn($admin, 'foo');

        $this->client->request('GET', $userIri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @test
     */
    public function admin_can_edit_other_users_directly()
    {
        $user = $this->createUser('user@domain.com', 'user', 'foo');
        $admin = $this->createUser('admin@domain.com', 'admin', 'foo');

        $userIri = $this->findIriBy(User::class, ['id' => $user->getId()]);

        $this->logIn($admin, 'foo');

        $this->client->request('PUT', $userIri, [
            'json' => [
                'name' => 'updated'
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @test
     */
    public function admin_can_delete_other_users_directly()
    {
        $user = $this->createUser('user@domain.com', 'user', 'foo');
        $this->createUserAndLogIn('admin@domain.com', 'foo', 'admin');

        $userIri = $this->findIriBy(User::class, ['id' => $user->getId()]);

        $this->client->request('DELETE', $userIri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(204);

        $this->client->request('DELETE', $userIri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * @test
     */
    public function admin_cant_delete_himself()
    {
        $admin = $this->createUserAndLogIn('admin@domain.com', 'foo', 'admin');

        $iri = $this->findIriBy(User::class, ['id' => $admin->getId()]);

        $this->client->request('DELETE', $iri, [
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertResponseStatusCodeSame(403);
    }
}
