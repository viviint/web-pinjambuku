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
        $serviceKey = env('SERVICE_KEY');

        \Illuminate\Support\Facades\Log::info("Mencoba mengirim notif ke: " . $notificationServiceUrl);

        if (empty($notificationServiceUrl)) {
            throw new \Exception('CRITICAL ERROR: NOTIFICATION_SERVICE_URL kosong! Worker gagal membaca .env');
        }

        $endpoint = "{$notificationServiceUrl}/api/notifications/send";
        
        \Illuminate\Support\Facades\Log::info("Menembak URL: " . $endpoint);

        \Illuminate\Support\Facades\Http::withHeaders(['X-Service-Key' => $serviceKey])
            ->timeout(5)
            ->post($endpoint, [
                'id_anggota' => $this->idAnggota,
                'pesan' => $this->pesan,
            ])
            ->throw();
    }
}
