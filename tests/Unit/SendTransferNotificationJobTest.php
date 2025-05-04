<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\Transfer;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendNotificationJob;
use App\Jobs\SendTransferNotificationJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class SendTransferNotificationJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_sends_notification_request_successfully()
    {
        Http::fake();

        $fakeData = [
            'email' => 'fake@email.com',
            'message' => 'teste',
        ];

        $job = new SendTransferNotificationJob($fakeData);
        $job->handle();

        Http::assertSent(function ($request) {
            return $request->url() === config('transfer.notification_url');
        });
    }

    public function test_job_logs_failure_on_final_attempt()
    {
        Http::fake([
            config('transfer.notification_url') => Http::response(null, 500),
        ]);

        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Falha final ao enviar notificação'
                    && isset($context['exception'])
                    && isset($context['data']);
            });

        $job = new SendTransferNotificationJob([
            'email' => 'fake@email.com',
            'message' => 'teste',
        ]);

        $job->failed(new \Exception('Falha simulada'));
    }
}
