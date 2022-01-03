<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class UserTest extends TestCase
{

    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();

        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }

    public function testCreateUser()
    {
        $faker = \Faker\Factory::create();
        $response = $this->json('post','api/register',[
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => '123456'
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);
    }

    public function testUserLogin()
    {
        $faker = \Faker\Factory::create();
        $user = $this->json('post','api/register',[
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => $password = '123456'
        ]);

        $response = $this->json('post','api/login',[
            'email' => $user['data']['email'],
            'password' => $password
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
    }
}
