<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class ReaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }

    public function testCreateReaderWithoutToken()
    {
        $data = [
            "name" => "Pedro Azevedo",
            "email" => "joaopedro@gmail.com.br",
            "phone" => "75 99990000",
            "address" => "Rua das Pedras, 150",
            "district" => "Centro",
            "city" => "Feira de Santana",
            "state" => "BA",
            "zipCode" => "44000000",
            "birthday" => "02/01/2001"
        ];

        $response = $this->json('post', 'api/readers', $data);
        $response->assertStatus(401);
        $response->assertJson(['message' => "Authorization Token not found"]);
    }

    public function testListReaders()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = '123456'),
        ]);

        $login = $this->json('post', 'api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->json('get', 'api/readers', [], ['Authorization' => "Bearer {$login['token']}"]);
        $response->assertStatus(200);
        $response->assertJson(['message' => "Leitores listados com sucesso"]);
    }

    public function testCreateReader()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = '123456'),
        ]);

        $login = $this->json('post', 'api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $data = [
            "name" => "Pedro Azevedo",
            "email" => "joaopedro@gmail.com.br",
            "phone" => "75 99990000",
            "address" => "Rua das Pedras, 150",
            "district" => "Centro",
            "city" => "Feira de Santana",
            "state" => "BA",
            "zipCode" => "44000000",
            "birthday" => "02/01/2001"
        ];

        $response = $this->json('post', 'api/readers', $data, ['Authorization' => "Bearer {$login['token']}"]);
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);
    }

    public function testCreateReaderFailsMissingRequiredFiels()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = '123456'),
        ]);

        $login = $this->json('post', 'api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->json('post', 'api/readers', [], ['Authorization' => "Bearer {$login['token']}"]);
        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid."
        ]);
    }
}
