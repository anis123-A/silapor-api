<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FotoLaporan extends Model
{
    protected $table = 'foto_laporan';
    protected $fillable = ['laporan_id', 'url_foto'];
    public $timestamps  = false;

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}
