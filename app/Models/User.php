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
        'nilai_rata_rata' => 'decimal:2'
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Ekstrakurikuler yang dibina oleh user (untuk pembina)
     */
    public function ekstrakurikulerSebagaiPembina()
    {
        return $this->hasMany(Ekstrakurikuler::class, 'pembina_id');
    }

    /**
     * Pendaftaran ekstrakurikuler user
     */
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    /**
     * Rekomendasi ekstrakurikuler untuk user
     */
    public function rekomendasis()
    {
        return $this->hasMany(Rekomendasi::class);
    }

    /**
     * Ekstrakurikuler yang diikuti user (melalui pendaftaran)
     */
    public function ekstrakurikulers()
    {
        return $this->belongsToMany(Ekstrakurikuler::class, 'pendaftarans')
            ->withPivot(['status', 'motivasi', 'pengalaman', 'harapan', 'tingkat_komitmen', 'created_at', 'updated_at'])
            ->withTimestamps();
    }

    /**
     * Absensi user (melalui pendaftaran)
     */
    public function absensis()
    {
        return $this->hasManyThrough(Absensi::class, Pendaftaran::class);
    }

    // ========== SCOPES ==========

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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ========== HELPER METHODS ==========

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

    /**
     * Check if user sudah terdaftar ekstrakurikuler
     */
    public function sudahTerdaftarEkstrakurikuler()
    {
        return $this->pendaftarans()->where('status', 'disetujui')->exists();
    }

    /**
     * Get minat as array
     */
    public function getMinatArrayAttribute()
    {
        if (!$this->minat) {
            return [];
        }

        if (is_string($this->minat)) {
            return json_decode($this->minat, true) ?? [];
        }

        return is_array($this->minat) ? $this->minat : [];
    }

    /**
     * Get ekstrakurikuler yang diikuti siswa (yang disetujui)
     */
    public function getEkstrakurikulerDiikutiAttribute()
    {
        return $this->ekstrakurikulers()
            ->wherePivot('status', 'disetujui')
            ->first();
    }

    /**
     * Check if user has complete profile for recommendation
     */
    public function hasCompleteProfile()
    {
        return !empty($this->minat) &&
            !empty($this->nilai_rata_rata) &&
            !empty($this->tanggal_lahir) &&
            !empty($this->jenis_kelamin);
    }

    /**
     * Get user's recommendation score for an ekstrakurikuler
     */
    public function getRecommendationScore($ekstrakurikulerId)
    {
        $rekomendasi = $this->rekomendasis()
            ->where('ekstrakurikuler_id', $ekstrakurikulerId)
            ->first();

        return $rekomendasi ? $rekomendasi->total_skor : 0;
    }

    /**
     * Get pendaftaran yang disetujui
     */
    public function getPendaftaranDisetujui()
    {
        return $this->pendaftarans()->where('status', 'disetujui')->first();
    }

    /**
     * Override update method untuk memastikan konsistensi data
     */
    public function update(array $attributes = [], array $options = [])
    {
        // Handle minat array conversion
        if (isset($attributes['minat']) && is_array($attributes['minat'])) {
            $attributes['minat'] = json_encode($attributes['minat']);
        }

        return parent::update($attributes, $options);
    }

    /**
     * Override fresh method untuk reload model dari database
     */
    public function fresh($with = [])
    {
        if (!$this->exists) {
            return null;
        }

        $key = $this->getKeyName();

        $fresh = static::newQueryWithoutScopes()
            ->with($with)
            ->where($key, $this->getKey())
            ->first();

        return $fresh ?: null;
    }

    /**
     * Get formatted display name
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->name;
        if ($this->nis && $this->isSiswa()) {
            $name .= " ({$this->nis})";
        }
        return $name;
    }

    /**
     * Get user's age
     */
    public function getAgeAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }
        return $this->tanggal_lahir->age;
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->telepon) {
            return null;
        }

        // Simple formatting for Indonesian phone numbers
        $phone = preg_replace('/\D/', '', $this->telepon);

        if (strlen($phone) >= 10) {
            return '+62 ' . substr($phone, 1, 3) . '-' . substr($phone, 4, 4) . '-' . substr($phone, 8);
        }

        return $this->telepon;
    }
}
