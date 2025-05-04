<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    function fakeSuccessfulTransferHttp()
    {
        Http::fake([
            config('transfer.auth_url') => Http::response(['message' => 'Autorizado'], 200),
        ]);
    }

    public function test_makes_a_valid_transfer()
    {
        $this->fakeSuccessfulTransferHttp();

        $payer = User::factory()->create([
            'type' => 'common',
            'wallet' => 500,
        ]);

        $payee = User::factory()->create();

        $token = $payer->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/transfer', [
                'payee' => $payee->id,
                'value' => 100,
            ]);

        $response->assertCreated()
            ->assertJson(['message' => 'Transferência realizada com sucesso!']);
    }

    public function test_prevents_shopkeeper_from_transferring()
    {
        $shopkeeper = User::factory()->create([
            'type' => 'shopkeeper',
            'wallet' => 500,
        ]);

        $payee = User::factory()->create();
        $token = $shopkeeper->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/transfer', [
                'payee_id' => $payee->id,
                'value' => 100,
            ]);

        $response->assertForbidden();
    }

    public function test_prevents_user_from_transferring_to_himself()
    {
        $payer = User::factory()->create([
            'type' => 'common',
            'wallet' => 500,
        ]);

        $token = $payer->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/transfer', [
                'payee' => $payer->id,
                'value' => 100,
            ]);

        $response->assertUnprocessable()
            ->assertJson(['message' => 'O destinatário da transferência deve ser diferente do remetente.']);
    }

    public function test_prevents_user_from_transferring_with_insufficient_wallet_balance()
    {
        $payer = User::factory()->create([
            'type' => 'common',
            'wallet' => 10,
        ]);

        $token = $payer->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/transfer', [
                'payee' => $payer->id,
                'value' => 100,
            ]);

        $response->assertUnprocessable()
            ->assertJson(['message' => 'O valor da transferência não pode ser maior que o saldo disponível na carteira. (and 1 more error)']);
    }

    public function test_fails_when_external_service_does_not_authorize()
    {
        Http::fake([
            config('transfer.auth_url') => Http::response(['message' => 'Autorizado'], 403),
        ]);
        $payer = User::factory()->create([
            'type' => 'common',
            'wallet' => 500,
        ]);

        $payee = User::factory()->create();

        $token = $payer->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/transfer', [
                'payee' => $payee->id,
                'value' => 100,
            ]);

        $response->assertForbidden()
            ->assertJson(['error' => 'Autorização negada pelo serviço externo.']);
    }
}
