<?php

namespace App\Repositories\User;

use App\DTO\User\ChangeUserWalletBalanceDTO;
use App\Enums\UserType;
use App\Models\User;

class UserRepository implements IUserRepository
{
    public function userIsShopkeeper(string $user_id): bool
    {
        $type = User::where('id', $user_id)->value('type');

        return $type === UserType::SHOPKEEPER->value;
    }

    public function getUserWalletBalance(string $user_id): float
    {
        return User::where('id', $user_id)->value('wallet');
    }

    public function getUserEmail(string $user_id): string
    {
        return User::where('id', $user_id)->value('email');
    }

    public function debitValueFromUserWallet(ChangeUserWalletBalanceDTO $dto)
    {
        $user = User::where('id', $dto->user_id)->lockForUpdate()->first();
        $user->debit($dto->value);
    }

    public function creditValueToUserWallet(ChangeUserWalletBalanceDTO $dto)
    {
        $user = User::where('id', $dto->user_id)->lockForUpdate()->first();
        $user->credit($dto->value);
    }
}
