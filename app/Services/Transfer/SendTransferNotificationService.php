<?php

namespace App\Services\Transfer;

use App\Jobs\SendTransferNotificationJob;
use App\Repositories\User\IUserRepository;

class SendTransferNotificationService
{
    public function __construct(
        private IUserRepository $userRepository,
    ) {}

    private const MESSAGE = 'Seu pagamento foi recebido!';

    public function execute(string $payee_id)
    {
        $email = $this->userRepository->getUserEmail($payee_id);

        SendTransferNotificationJob::dispatch([
            'email' => $email,
            'message' => self::MESSAGE,
        ]);
    }
}
