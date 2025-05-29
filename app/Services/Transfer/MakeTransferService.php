<?php

namespace App\Services\Transfer;

use App\DTO\Transfer\CreateTransferDTO;
use App\DTO\Transfer\MakeTransferDTO;
use App\DTO\User\ChangeUserWalletBalanceDTO;
use App\Events\TransferCompleted;
use App\Exceptions\Transfer\NotEnoughBalanceException;
use App\Exceptions\Transfer\ShopkeeperTransferException;
use App\Repositories\Transfer\IExternalAuthRepository;
use App\Repositories\Transfer\ITransferRepository;
use App\Repositories\User\IUserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class MakeTransferService
{
    public function __construct(
        private IExternalAuthRepository $externalAuthRepository,
        private ITransferRepository $transferRepository,
        private IUserRepository $userRepository,
        private SendTransferNotificationService $sendTransferNotificationService
    ) {}

    public function execute(MakeTransferDTO $dto): void
    {
        DB::beginTransaction();

        try {
            $this->validatePayerIsShopkeeper($dto->payer_id);
            $this->validatePayerHasEnoughBalance($dto->payer_id, $dto->value);

            $this->externalAuthRepository->authorize();

            $this->adjustWalletBalances($dto->payer_id, $dto->payee_id, $dto->value);
            $this->transferRepository->create(new CreateTransferDTO(
                payer_id: $dto->payer_id,
                payee_id: $dto->payee_id,
                value: $dto->value
            ));

            // Coloca notificação na fila para ser enviada
            event(new TransferCompleted($dto->payee_id));
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    private function adjustWalletBalances(string $payer_id, string $payee_id, float $value): void
    {
        $this->userRepository->debitValueFromUserWallet(new ChangeUserWalletBalanceDTO(
            user_id: $payer_id,
            value: $value
        ));

        $this->userRepository->creditValueToUserWallet(new ChangeUserWalletBalanceDTO(
            user_id: $payee_id,
            value: $value
        ));
    }

    private function validatePayerIsShopkeeper(string $payer_id): void
    {
        $isShopkeeper = $this->userRepository->userIsShopkeeper($payer_id);
        if ($isShopkeeper) {
            throw new ShopkeeperTransferException();
        }
    }

    private function validatePayerHasEnoughBalance(string $payer_id, float $value): void
    {
        $payerBalance = $this->userRepository->getUserWalletBalance($payer_id);
        if ($payerBalance < $value) {
            throw new NotEnoughBalanceException();
        }
    }
}
