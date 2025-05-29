<?php

namespace App\Events;

class TransferCompleted
{
    public function __construct(
        public string $payeeId,
    ) {}
}
