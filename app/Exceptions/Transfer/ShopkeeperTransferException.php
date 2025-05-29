<?php

namespace App\Exceptions\Transfer;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ShopkeeperTransferException extends HttpException
{
    public function __construct($message = 'Lojistas não podem realizar transferências.', int $statusCode = Response::HTTP_FORBIDDEN,)
    {
        parent::__construct($statusCode, $message);
    }
}
