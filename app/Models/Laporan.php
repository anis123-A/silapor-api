<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $fillable = [
        'user_id', 'kategori_id', 'judul',
        'deskripsi', 'lokasi', 'status', 'catatan_admin',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function foto()
    {
        return $this->hasMany(FotoLaporan::class, 'laporan_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }
}
