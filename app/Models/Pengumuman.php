<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    protected $table = 'pengumumans';
    protected $fillable = [
        'judul',
        'konten',
        'ekstrakurikuler_id',
        'dibuat_oleh',
        'is_penting'
    ];

    protected $casts = [
        'is_penting' => 'boolean',
    ];

    // Relationships
    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Scopes
    public function scopePenting($query)
    {
        return $query->where('is_penting', true);
    }
}
