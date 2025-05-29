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
