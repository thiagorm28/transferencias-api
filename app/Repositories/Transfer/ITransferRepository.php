<?php

namespace App\Repositories\Transfer;

use App\DTO\Transfer\CreateTransferDTO;

interface ITransferRepository
{
    public function create(CreateTransferDTO $dto): void;
}
