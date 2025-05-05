<?php

namespace Tests\Unit\Models;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfer_belongs_to_payer()
    {
        $payer = User::factory()->create();
        $payee = User::factory()->create();

        $transfer = Transfer::factory()->create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
        ]);

        $this->assertInstanceOf(User::class, $transfer->payer);
        $this->assertEquals($payer->id, $transfer->payer->id);
    }

    public function test_transfer_belongs_to_payee()
    {
        $payer = User::factory()->create();
        $payee = User::factory()->create();

        $transfer = Transfer::factory()->create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
        ]);

        $this->assertInstanceOf(User::class, $transfer->payee);
        $this->assertEquals($payee->id, $transfer->payee->id);
    }
}
