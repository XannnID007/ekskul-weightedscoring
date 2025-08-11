<?php
// app/Providers/EventServiceProvider.php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Import Model Events
use App\Models\Pendaftaran;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Event untuk mengupdate kapasitas ekstrakurikuler
        Pendaftaran::saved(function ($pendaftaran) {
            $this->updateEkstrakurikulerCapacity($pendaftaran);
        });

        Pendaftaran::deleted(function ($pendaftaran) {
            $this->updateEkstrakurikulerCapacity($pendaftaran);
        });
    }

    /**
     * Update kapasitas ekstrakurikuler berdasarkan pendaftaran yang disetujui
     */
    private function updateEkstrakurikulerCapacity($pendaftaran)
    {
        if ($pendaftaran->ekstrakurikuler) {
            // Hitung jumlah siswa yang sudah disetujui
            $jumlahDisetujui = $pendaftaran->ekstrakurikuler
                ->pendaftarans()
                ->where('status', 'disetujui')
                ->count();

            // Update field peserta_saat_ini
            $pendaftaran->ekstrakurikuler->update([
                'peserta_saat_ini' => $jumlahDisetujui
            ]);
        }
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
