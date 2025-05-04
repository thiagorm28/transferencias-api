<?php

namespace App\DTO\Transfer;

class CreateTransferDTO
{
    public string $payer_id;

    public string $payee_id;

    public float $value;

    public function __construct(string $payer_id, string $payee_id, float $value)
    {
        $this->payer_id = $payer_id;
        $this->payee_id = $payee_id;
        $this->value = $value;
    }
}
