<?php

namespace App\DTO\Transfer;

use App\Models\User;

class MakeTransferDTO
{
    public User $payer;
    public string $payee_id;
    public float $value;

    public function __construct(User $payer, string $payee_id, float $value)
    {
        $this->payer = $payer;
        $this->payee_id = $payee_id;
        $this->value = $value;
    }
}
