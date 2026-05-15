<?php

namespace App\Jobs;

use App\Services\Notification\SmsNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BroadcastSmsAnnouncement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $numbers,
        public string $message
    ) {
    }

    public function handle(SmsNotificationService $smsService): void
    {
        if (empty($this->numbers) || empty($this->message)) {
            return;
        }

        try {
            $smsService->sendBulk($this->numbers, $this->message);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast SMS announcement: ' . $e->getMessage(), [
                'count' => count($this->numbers),
                'message' => $this->message,
            ]);
            
            throw $e;
        }
    }
}
