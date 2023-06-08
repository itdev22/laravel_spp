<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanSiswa extends Model
{
    use HasFactory;

    protected $table = 'tagihan_siswa';

    protected $fillable = [
        'siswa_id',
        'tagihan_id',
        'nominal',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
}
