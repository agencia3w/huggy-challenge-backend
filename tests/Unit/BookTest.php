<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class BookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }

    public function testCreateBookWithoutToken()
    {
        $data = [
            "title" => "Código Limpo Python",
            "genre" => "Tecnologia",
            "author" => "Carlos Neres",
            "year" => "2021",
            "pages" => "203",
            "language" => "Português",
            "edition" => "1ª",
            "publisher_name" => "Pearson",
            "publisher_code" => "20",
            "publisher_phone" => "750090990",
            "isbn" => "33-0984-9983"
        ];

        $response = $this->json('post', 'api/books', $data);
        $response->assertStatus(401);
        $response->assertJson(['message' => "Authorization Token not found"]);
    }

    public function testListBooks()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = '123456'),
        ]);

        $login = $this->json('post', 'api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->json('get', 'api/books', [], ['Authorization' => "Bearer {$login['token']}"]);
        $response->assertStatus(200);
        $response->assertJson(['message' => "Livros listados com sucesso"]);
    }

    public function testCreateBooks()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = '123456'),
        ]);

        $login = $this->json('post', 'api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $data = [
            "title" => "Código Limpo Delphi",
            "genre" => "Tecnologia",
            "author" => "Carlos Neres",
            "year" => "2021",
            "pages" => "203",
            "language" => "Português",
            "edition" => "1ª",
            "publisher_name" => "Pearson",
            "publisher_code" => "20",
            "publisher_phone" => "750090990",
            "isbn" => "33-0984-9983"
        ];

        $response = $this->json('post', 'api/books', $data, ['Authorization' => "Bearer {$login['token']}"]);
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);
    }

    public function testCreateBooksFailsMissingRequiredFiels()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = '123456'),
        ]);

        $login = $this->json('post', 'api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->json('post', 'api/books', [], ['Authorization' => "Bearer {$login['token']}"]);
        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid."
        ]);
    }
}
