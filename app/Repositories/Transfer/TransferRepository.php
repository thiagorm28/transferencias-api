<?php

namespace App\Repositories\Transfer;

use App\DTO\Transfer\CreateTransferDTO;
use App\Models\Transfer;

class TransferRepository implements ITransferRepository
{
    public function create(CreateTransferDTO $dto): void
    {
        Transfer::create(
            [
                'payer_id' => $dto->payer_id,
                'payee_id' => $dto->payee_id,
                'value' => $dto->value,
            ]
        );
    }
}
