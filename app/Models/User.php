<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_MAHASISWA = 'mahasiswa';

    protected $fillable = [
        'nama', 'nim', 'email', 'password',
        'role', 'fakultas_id', 'prodi_id', 'foto_profil', 'is_aktif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['is_aktif' => 'boolean'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }
}
