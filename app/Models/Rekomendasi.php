<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ekstrakurikuler_id',
        'skor_minat',
        'skor_akademik',
        'skor_jadwal',
        'total_skor',
        'alasan'
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

    // Scopes
    public function scopeTerbaik($query, $limit = 3)
    {
        return $query->orderBy('total_skor', 'desc')->limit($limit);
    }
}
