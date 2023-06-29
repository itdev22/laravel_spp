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
        'tanggal_bayar',
        'nisn',
        'nominal',
        'status',
        'metode',
        'url_payment'
    ];

    public function getJumlahBayarAttribute($value)
    {
        return "Rp " . number_format($value, 0, 2, '.');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tagihansiswa()
    {
        return $this->belongsTo(TagihanSiswa::class, 'tagihansiswa_id');
    }
}
