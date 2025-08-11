<?php
// app/Models/Pendaftaran.php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ekstrakurikuler_id',
        'motivasi',
        'pengalaman',
        'harapan',
        'tingkat_komitmen',
        'status',
        'alasan_penolakan',
        'disetujui_pada',
        'disetujui_oleh'
    ];

    protected $casts = [
        'disetujui_pada' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    // Helper methods
    public function getPersentaseKehadiranAttribute()
    {
        $totalAbsensi = $this->absensis()->count();
        if ($totalAbsensi == 0) return 0;

        $hadir = $this->absensis()->where('status', 'hadir')->count();
        return round(($hadir / $totalAbsensi) * 100, 2);
    }

    /**
     * Boot method untuk menangani events
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika status pendaftaran berubah
        static::updated(function ($pendaftaran) {
            // Cek apakah status yang berubah
            if ($pendaftaran->isDirty('status')) {
                self::updateEkstrakurikulerCapacity($pendaftaran);
            }
        });

        // Event ketika pendaftaran dihapus
        static::deleted(function ($pendaftaran) {
            self::updateEkstrakurikulerCapacity($pendaftaran);
        });

        // Event ketika pendaftaran dibuat (untuk backup)
        static::created(function ($pendaftaran) {
            self::updateEkstrakurikulerCapacity($pendaftaran);
        });
    }

    /**
     * Update kapasitas ekstrakurikuler
     */
    private static function updateEkstrakurikulerCapacity($pendaftaran)
    {
        if ($pendaftaran->ekstrakurikuler) {
            // Hitung jumlah siswa yang sudah disetujui
            $jumlahDisetujui = $pendaftaran->ekstrakurikuler
                ->pendaftarans()
                ->where('status', 'disetujui')
                ->count();

            // Update field peserta_saat_ini tanpa trigger event lagi
            $pendaftaran->ekstrakurikuler->updateQuietly([
                'peserta_saat_ini' => $jumlahDisetujui
            ]);

            // Log untuk debugging (opsional)
            Log::info("Updated ekstrakurikuler capacity", [
                'ekstrakurikuler_id' => $pendaftaran->ekstrakurikuler->id,
                'nama' => $pendaftaran->ekstrakurikuler->nama,
                'peserta_saat_ini' => $jumlahDisetujui,
                'kapasitas_maksimal' => $pendaftaran->ekstrakurikuler->kapasitas_maksimal
            ]);
        }
    }
}
