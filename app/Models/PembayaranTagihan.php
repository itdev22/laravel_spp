<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranTagihan extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_tagihans';

    protected $fillable = [
        'kode_pembayaran',
        'petugas_id',
        'siswa_id',
        'tagihansiswa_id',
        'nisn',
        'status'
    ];
}
