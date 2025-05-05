<?php

namespace Tests\Unit;

use App\Enums\UserType;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_a_shopkeeper()
    {
        $user = User::factory()->create(['type' => UserType::SHOPKEEPER->value]);

        $this->assertTrue($user->isShopkeeper());
    }

    public function test_user_can_have_sufficient_balance()
    {
        $user = User::factory()->create(['wallet' => 100]);

        $this->assertTrue($user->hasEnoughBalance(50));
        $this->assertFalse($user->hasEnoughBalance(150));
    }

    public function test_user_wallet_can_be_debited()
    {
        $user = User::factory()->create(['wallet' => 100]);

        $user->debit(40);

        $this->assertEquals(60, $user->fresh()->wallet);
    }

    public function test_user_wallet_can_be_credited()
    {
        $user = User::factory()->create(['wallet' => 100]);

        $user->credit(25);

        $this->assertEquals(125, $user->fresh()->wallet);
    }

    public function test_user_has_many_transfers_sent()
    {
        $user = User::factory()->create();
        $transfers = Transfer::factory()->count(2)->create([
            'payer_id' => $user->id,
        ]);

        $this->assertCount(2, $user->transfersSent);
        $this->assertTrue($user->transfersSent->contains($transfers->first()));
    }

    public function test_user_has_many_transfers_received()
    {
        $user = User::factory()->create();
        $transfers = Transfer::factory()->count(3)->create([
            'payee_id' => $user->id,
        ]);

        $this->assertCount(3, $user->transfersReceived);
        $this->assertTrue($user->transfersReceived->contains($transfers->last()));
    }
}
