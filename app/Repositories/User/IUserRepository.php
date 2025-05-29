<?php

namespace App\Repositories\User;

use App\DTO\User\ChangeUserWalletBalanceDTO;

interface IUserRepository
{
    public function userIsShopkeeper(string $user_id): bool;

    public function getUserWalletBalance(string $user_id): float;

    public function getUserEmail(string $user_id): string;

    public function debitValueFromUserWallet(ChangeUserWalletBalanceDTO $dto);

    public function creditValueToUserWallet(ChangeUserWalletBalanceDTO $dto);
}
