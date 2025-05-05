<?php

namespace Database\Factories;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferFactory extends Factory
{
    protected $model = Transfer::class;

    public function definition(): array
    {
        return [
            'payer_id' => User::factory(),
            'payee_id' => User::factory(),
            'value' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
