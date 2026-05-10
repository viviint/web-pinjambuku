<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;

    public function __construct(
        private readonly int $idAnggota,
        private readonly string $pesan,
    ) {
    }

    public function handle(): void
    {
        $notificationServiceUrl = env('NOTIFICATION_SERVICE_URL');

        if (empty($notificationServiceUrl)) {
            return;
        }

        Http::withHeaders(['X-Service-Key' => env('SERVICE_KEY')])
            ->timeout(5)
            ->post("{$notificationServiceUrl}/api/notifications/send", [
                'id_anggota' => $this->idAnggota,
                'pesan' => $this->pesan,
            ])
            ->throw();
    }
}
