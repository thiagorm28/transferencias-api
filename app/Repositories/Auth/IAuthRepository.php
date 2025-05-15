<?php

namespace App\Repositories\Auth;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\LogoutDTO;
use App\DTO\Auth\RegisterUserDTO;

interface IAuthRepository
{
    public function registerUser(RegisterUserDTO $dto): void;

    public function login(LoginDTO $dto): string;

    public function logout(LogoutDTO $dto): void;
}
