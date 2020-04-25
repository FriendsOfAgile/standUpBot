<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-25
 * Time: 20:31
 */

namespace App\Tests\Functional;


use App\Test\CustomApiTestCase;

class StandUpConfigResourceTest extends CustomApiTestCase
{
    public function can_get_only_current_user_items()
    {
        $user1 = $this->createUser('user1@domain.com', 'user', 'foo');
        $user2 = $this->createUser('user2@domain.com', 'user', 'foo');


    }
}