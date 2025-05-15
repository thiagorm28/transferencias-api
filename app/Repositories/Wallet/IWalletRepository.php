<?php

namespace App\Repositories\Wallet;

use App\DTO\Wallet\ChangeUserWalletBalanceDTO;

interface IWalletRepository
{
    public function debitValueFromUserWallet(ChangeUserWalletBalanceDTO $dto);

    public function creditValueToUserWallet(ChangeUserWalletBalanceDTO $dto);
}
