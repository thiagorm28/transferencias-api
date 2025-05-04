<?php

namespace App\Repositories\Auth;

use App\DTO\Auth\RegisterUserDTO;
use App\Models\User;

class RegisterUserRepository
{
    public function execute(RegisterUserDTO $dto): mixed
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'cpf_cnpj' => $dto->cpf_cnpj,
            'password' => bcrypt($dto->password),
            'type' => $dto->type
        ]);
    }
}
