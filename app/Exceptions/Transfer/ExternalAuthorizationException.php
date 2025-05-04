<?php

namespace App\Exceptions\Transfer;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ExternalAuthorizationException extends HttpException
{
    public function __construct(
        string $message = 'Falha na autorização com o serviço externo.',
        int $statusCode = 502,
    ) {
        parent::__construct($statusCode, $message);
    }
}
