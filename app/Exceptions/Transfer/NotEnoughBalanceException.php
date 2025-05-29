<?php

namespace App\Exceptions\Transfer;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NotEnoughBalanceException extends HttpException
{
    public function __construct($message = 'Saldo insuficiente para realizar transferência.', int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY,)
    {
        parent::__construct($statusCode, $message);
    }
}
