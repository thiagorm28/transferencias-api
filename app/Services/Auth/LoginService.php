<?php

namespace App\Services\Auth;

use App\DTO\Auth\LoginDTO;
use App\Exceptions\Auth\WrongPasswordExeception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function execute(LoginDTO $dto): string
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new WrongPasswordExeception;
        }

        return $user->createToken('auth_token')->plainTextToken;
    }
}
