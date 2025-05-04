<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registers_a_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'cpf_cnpj' => '12345678901',
            'password' => 'password',
            'password_confirmation' => 'password',
            'type' => 'common',
        ]);

        $response->assertCreated()
            ->assertJson(['message' => 'Usuário criado com sucesso!']);
    }

    public function test_logs_in_a_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_not_logs_in_a_user_when_password_is_wrong()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertUnprocessable()
            ->assertJson(['error' => 'Senha inválida!']);
    }

    public function test_logs_out_a_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/logout');

        $response->assertOk()
            ->assertJson(['message' => 'Logout realizado com sucesso.']);
    }
}
