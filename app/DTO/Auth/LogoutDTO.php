<?php

namespace App\DTO\Auth;

class LogoutDTO
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
