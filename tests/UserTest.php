<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    public function testUsersTest()
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

    public function testUsersWithChampionshipsTest()
    {
        $this->get('api/v1/users?extra=championships')
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
                    'apikey',
                    'championships' => [
                        '*' => [
                            'id',
                            'name',
                            'date',
                            'pivot' => [
                                'user_id',
                                'championship_id'
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
    public function testUserWithNoIdTest()
    {
        $this->get('api/v1/user')
        ->seeStatusCode(405);
    }
}
