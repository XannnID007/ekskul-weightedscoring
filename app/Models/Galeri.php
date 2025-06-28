<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $fillable = [
        'ekstrakurikuler_id',
        'judul',
        'deskripsi',
        'path_file',
        'tipe',
        'diupload_oleh'
    ];

    // Relationships
    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'diupload_oleh');
    }

    // Scopes
    public function scopeGambar($query)
    {
        return $query->where('tipe', 'gambar');
    }

    public function scopeVideo($query)
    {
        return $query->where('tipe', 'video');
    }
}
