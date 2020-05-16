<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    public function testUserTest()
    {
        $this->get('api/v1/users')
        ->seeStatusCode(200)
        ->seeJsonStructure([
            'code',
            'message',
            'users' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'number',
                    'apikey'
                ]
            ]
        ]);
    }
}
