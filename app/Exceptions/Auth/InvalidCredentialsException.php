<?php

namespace App\Exceptions\Auth;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct($message = 'Credenciais inválidas!', $code = 422)
    {
        parent::__construct($message, $code);
    }
}
