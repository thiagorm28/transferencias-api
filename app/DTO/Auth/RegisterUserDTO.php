<?php

namespace App\DTO\Auth;

class RegisterUserDTO
{
    public string $name;
    public string $email;
    public string $cpf_cnpj;
    public string $password;
    public string $type;

    public function __construct(string $name, string $email, string $cpf_cnpj, string $password, string $type)
    {
        $this->name = $name;
        $this->email = $email;
        $this->cpf_cnpj = $cpf_cnpj;
        $this->password = $password;
        $this->type = $type;
    }
}
