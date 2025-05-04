<?php

namespace App\Exceptions\Transfer;

use Illuminate\Auth\Access\AuthorizationException;

class ShopkeeperTransferException extends AuthorizationException
{
    public function __construct($message = 'Lojistas não podem realizar transferências.')
    {
        parent::__construct($message);
    }
}
