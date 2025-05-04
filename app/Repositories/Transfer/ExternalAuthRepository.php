<?php

namespace App\Repositories\Transfer;

use App\Exceptions\Transfer\ExternalAuthorizationException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ExternalAuthRepository
{
    public function authorize(): bool
    {
        try {
            $url = config('transfer.auth_url');
            $response = Http::timeout(5)->get($url);
        } catch (ConnectionException $e) {
            throw new ExternalAuthorizationException('Erro de conexão com o serviço externo (timeout).', Response::HTTP_BAD_GATEWAY);
        }

        if ($response->status() === Response::HTTP_OK) {
            return true;
        }

        throw new ExternalAuthorizationException('Autorização negada pelo serviço externo.', Response::HTTP_FORBIDDEN);
    }
}
