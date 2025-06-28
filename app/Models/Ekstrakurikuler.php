<?php

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
}
