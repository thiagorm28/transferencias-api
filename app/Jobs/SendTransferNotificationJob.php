<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Classe de Job responsável pelo envio de notificações de transferência.
 *
 * ## Tentativas e Retentativas
 * - O job irá tentar ser executado até n vezes (configurado em `$tries`).
 * - Em caso de falha, ele irá esperar n segundos antes de tentar novamente (configurado em `$backoff`).
 *
 * ## Parâmetros
 * - `$data` (array): Dados a serem enviados na notificação. São fornecidos ao job
 *   no momento da criação.
 */

class SendTransferNotificationJob implements ShouldQueue
{
    use Queueable;

    public $tries = 10;
    public $backoff = 1;
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $url = config('transfer.notification_url');
        $response = Http::timeout(5)->post($url, $this->data);

        if (!$response->successful()) {
            throw new \Exception('Falha no envio da notificação');
        }

        Log::info('Notificação enviada com sucesso', [
            'data' => $this->data,
            'response' => $response->body()
        ]);
    }

    public function failed(\Throwable $e)
    {
        Log::error('Falha final ao enviar notificação', [
            'exception' => $e->getMessage(),
            'data' => $this->data,
            'error_code' => $e->getCode()
        ]);
    }
}
