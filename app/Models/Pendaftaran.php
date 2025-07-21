<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
