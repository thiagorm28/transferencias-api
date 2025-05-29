<?php

namespace App\Listeners;

use App\Events\TransferCompleted;
use App\Services\Transfer\SendTransferNotificationService;

class SendTransferNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransferCompleted $event): void
    {
        app(SendTransferNotificationService::class)->execute($event->payeeId);
    }
}
