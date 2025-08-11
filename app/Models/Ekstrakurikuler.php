<?php
// app/Models/Ekstrakurikuler.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'gambar',
        'kapasitas_maksimal',
        'peserta_saat_ini',
        'jadwal',
        'kategori',
        'nilai_minimal',
        'pembina_id',
        'is_active'
    ];

    protected $casts = [
        'jadwal' => 'array',
        'kategori' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pembina()
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function siswaTerdaftar()
    {
        return $this->belongsToMany(User::class, 'pendaftarans')
            ->withPivot('status', 'motivasi', 'pengalaman', 'harapan', 'tingkat_komitmen')
            ->withTimestamps();
    }

    public function siswaDisetujui()
    {
        return $this->belongsToMany(User::class, 'pendaftarans')
            ->wherePivot('status', 'disetujui')
            ->withPivot('status', 'motivasi', 'pengalaman', 'harapan', 'tingkat_komitmen')
            ->withTimestamps();
    }

    public function rekomendasis()
    {
        return $this->hasMany(Rekomendasi::class);
    }

    public function pengumumans()
    {
        return $this->hasMany(Pengumuman::class);
    }

    public function galeris()
    {
        return $this->hasMany(Galeri::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTersedia($query)
    {
        return $query->whereColumn('peserta_saat_ini', '<', 'kapasitas_maksimal');
    }

    // Helper methods
    public function masihBisaDaftar()
    {
        return $this->peserta_saat_ini < $this->kapasitas_maksimal && $this->is_active;
    }

    public function getJadwalStringAttribute()
    {
        $jadwal = $this->jadwal;
        return isset($jadwal['hari']) && isset($jadwal['waktu'])
            ? ucfirst($jadwal['hari']) . ', ' . $jadwal['waktu']
            : 'Jadwal belum ditentukan';
    }

    public function getKategoriStringAttribute()
    {
        return is_array($this->kategori) ? implode(', ', $this->kategori) : '';
    }

    /**
     * Hitung dan update peserta saat ini secara manual
     * Useful untuk maintenance atau sinkronisasi data
     */
    public function recalculateCapacity()
    {
        $jumlahDisetujui = $this->pendaftarans()
            ->where('status', 'disetujui')
            ->count();

        $this->updateQuietly([
            'peserta_saat_ini' => $jumlahDisetujui
        ]);

        return $jumlahDisetujui;
    }

    /**
     * Get persentase kapasitas terisi
     */
    public function getPersentaseKapasitasAttribute()
    {
        if ($this->kapasitas_maksimal <= 0) {
            return 0;
        }

        return round(($this->peserta_saat_ini / $this->kapasitas_maksimal) * 100, 1);
    }

    /**
     * Get sisa kapasitas
     */
    public function getSisaKapasitasAttribute()
    {
        return max(0, $this->kapasitas_maksimal - $this->peserta_saat_ini);
    }

    /**
     * Check apakah hampir penuh (80% atau lebih)
     */
    public function isHampirPenuh()
    {
        return $this->persentase_kapasitas >= 80;
    }

    /**
     * Get status kapasitas dalam bentuk string
     */
    public function getStatusKapasitasAttribute()
    {
        if ($this->peserta_saat_ini >= $this->kapasitas_maksimal) {
            return 'penuh';
        } elseif ($this->isHampirPenuh()) {
            return 'hampir_penuh';
        } elseif ($this->peserta_saat_ini > 0) {
            return 'tersedia';
        } else {
            return 'kosong';
        }
    }

    /**
     * Boot method untuk handling events
     */
    protected static function boot()
    {
        parent::boot();

        // Ketika ekstrakurikuler dibuat, set peserta_saat_ini = 0
        static::creating(function ($ekstrakurikuler) {
            if (!isset($ekstrakurikuler->peserta_saat_ini)) {
                $ekstrakurikuler->peserta_saat_ini = 0;
            }
        });

        // Ketika ekstrakurikuler dihapus, bersihkan data terkait
        static::deleting(function ($ekstrakurikuler) {
            // Hapus semua pendaftaran terkait
            $ekstrakurikuler->pendaftarans()->delete();
            $ekstrakurikuler->rekomendasis()->delete();

            if ($ekstrakurikuler->pengumumans()) {
                $ekstrakurikuler->pengumumans()->delete();
            }

            if ($ekstrakurikuler->galeris()) {
                $ekstrakurikuler->galeris()->delete();
            }
        });
    }
}
