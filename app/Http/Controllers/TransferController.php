<?php

namespace App\Http\Controllers;

use App\DTO\Transfer\MakeTransferDTO;
use App\Http\Requests\Transfer\MakeTransferRequest;
use App\Services\Transfer\MakeTransferService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransferController extends Controller
{
    public function __construct(private MakeTransferService $makeTransferService) {}

    /**
     * Faz uma nova transferência.
     */
    public function makeTransfer(MakeTransferRequest $request)
    {
        try {
            $this->makeTransferService->execute(new MakeTransferDTO(
                payer_id: $request->user()->id,
                payee_id: $request->payee,
                value: $request->value,
            ));
        } catch (HttpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'Transferência realizada com sucesso!'], Response::HTTP_CREATED);
    }
}
