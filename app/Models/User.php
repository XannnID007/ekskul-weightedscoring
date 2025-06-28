<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nis',
        'telepon',
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'nilai_rata_rata',
        'minat',
        'prestasi',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'minat' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function ekstrakurikulerSebagaiPembina()
    {
        return $this->hasMany(Ekstrakurikuler::class, 'pembina_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function rekomendasis()
    {
        return $this->hasMany(Rekomendasi::class);
    }

    public function ekstrakurikulers()
    {
        return $this->belongsToMany(Ekstrakurikuler::class, 'pendaftarans')
            ->withPivot('status', 'motivasi', 'pengalaman', 'harapan', 'tingkat_komitmen')
            ->withTimestamps();
    }

    // Scopes
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopePembina($query)
    {
        return $query->where('role', 'pembina');
    }

    public function scopeSiswa($query)
    {
        return $query->where('role', 'siswa');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPembina()
    {
        return $this->role === 'pembina';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function sudahTerdaftarEkstrakurikuler()
    {
        return $this->pendaftarans()->where('status', 'disetujui')->exists();
    }

    public function getMinatArrayAttribute()
    {
        return json_decode($this->minat, true) ?? [];
    }
}
