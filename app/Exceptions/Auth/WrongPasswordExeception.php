<?php

namespace App\Exceptions;

use Exception;

class WrongPasswordExeception extends Exception
{
    public function __construct($message = 'Senha inválida!', $code = 422)
    {
        parent::__construct($message, $code);
    }
}
