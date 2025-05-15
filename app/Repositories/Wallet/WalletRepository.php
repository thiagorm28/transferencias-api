<?php

namespace App\Repositories\Wallet;

use App\DTO\Wallet\ChangeUserWalletBalanceDTO;
use App\Models\User;

class WalletRepository implements IWalletRepository
{
    public function debitValueFromUserWallet(ChangeUserWalletBalanceDTO $dto)
    {
        $user = User::find($dto->user_id);
        $user->debit($dto->value);
    }

    public function creditValueToUserWallet(ChangeUserWalletBalanceDTO $dto)
    {
        $user = User::find($dto->user_id);
        $user->credit($dto->value);
    }
}
