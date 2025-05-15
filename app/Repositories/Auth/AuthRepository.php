<?php

namespace App\Repositories\Auth;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\LogoutDTO;
use App\DTO\Auth\RegisterUserDTO;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthRepository implements IAuthRepository
{
    public function registerUser(RegisterUserDTO $dto): void
    {
        User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'cpf_cnpj' => $dto->cpf_cnpj,
            'password' => bcrypt($dto->password),
            'type' => $dto->type,
        ]);
    }

    public function login(LoginDTO $dto): string
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function logout(LogoutDTO $dto): void
    {
        $accessToken = PersonalAccessToken::findToken($dto->token);
        if ($accessToken) {
            $accessToken->delete();
        }
    }
}
