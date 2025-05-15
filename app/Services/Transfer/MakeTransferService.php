<?php

namespace App\Services\Transfer;

use App\DTO\Transfer\CreateTransferDTO;
use App\DTO\Transfer\MakeTransferDTO;
use App\DTO\Wallet\ChangeUserWalletBalanceDTO;
use App\Models\User;
use App\Repositories\Transfer\IExternalAuthRepository;
use App\Repositories\Transfer\ITransferRepository;
use App\Repositories\Wallet\IWalletRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MakeTransferService
{
    private $sendTransferNotificationService;

    public function __construct(
        private IExternalAuthRepository $externalAuthRepository,
        private ITransferRepository $transferRepository,
        private IWalletRepository $walletRepository,
    ) {
        $this->sendTransferNotificationService = new SendTransferNotificationService;
    }

    public function execute(MakeTransferDTO $dto): void
    {
        DB::beginTransaction();

        try {
            $this->externalAuthRepository->authorize();
            $this->adjustWalletBalances($dto->payer, $dto->payee_id, $dto->value);
            $this->transferRepository->create(new CreateTransferDTO(
                payer_id: $dto->payer->id,
                payee_id: $dto->payee_id,
                value: $dto->value
            ));

            // Coloca notificação na fila para ser enviada
            $this->sendTransferNotificationService->execute($dto->payee_id);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    private function adjustWalletBalances(User $payer, string $payee_id, float $value): void
    {
        $this->walletRepository->debitValueFromUserWallet(new ChangeUserWalletBalanceDTO(
            user_id: $payer->id,
            value: $value
        ));

        $this->walletRepository->creditValueToUserWallet(new ChangeUserWalletBalanceDTO(
            user_id: $payee_id,
            value: $value
        ));
    }
}
