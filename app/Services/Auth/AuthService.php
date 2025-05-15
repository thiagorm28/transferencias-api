<?php

namespace App\Services\Auth;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\LogoutDTO;
use App\DTO\Auth\RegisterUserDTO;
use App\Repositories\Auth\IAuthRepository;

class AuthService
{
    public function __construct(
        private IAuthRepository $authRepository
    ) {}

    public function registerUser(RegisterUserDTO $dto): void
    {
        $this->authRepository->registerUser($dto);
    }

    public function login(LoginDTO $dto): string
    {
        return $this->authRepository->login($dto);
    }

    public function logout(LogoutDTO $dto): void
    {
        $this->authRepository->logout($dto);
    }
}
