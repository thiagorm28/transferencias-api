<?php

namespace App\Services\Transfer;

use App\Jobs\SendTransferNotificationJob;
use App\Models\User;

class SendTransferNotificationService
{
    private const MESSAGE = 'Seu pagamento foi recebido!';

    public function execute(string $payee_id)
    {
        $user = User::find($payee_id);

        SendTransferNotificationJob::dispatch([
            'email' => $user->email,
            'message' => self::MESSAGE,
        ]);
    }
}
