<?php

namespace App\Models;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;
    protected $table = 'tagihan';

    protected $fillable = [
        'nama_tagihan',
        'kode_tagihan',
        'kelas_id',
        'siswa_id',
        'tagihan_id',
        'nominal',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
