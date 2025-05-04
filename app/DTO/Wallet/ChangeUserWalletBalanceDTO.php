<?php

namespace App\DTO\Wallet;

class ChangeUserWalletBalanceDTO
{
    public string $user_id;

    public float $value;

    public function __construct(string $user_id, float $value)
    {
        $this->user_id = $user_id;
        $this->value = $value;
    }
}
