<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;
use App\Models\Tagihan;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
    	'nama_kelas',
    	'kompetensi_keahlian',
    ];

    public function siswa()
    {
    	return $this->hasMany(Siswa::class);
    }
    public function tagihan()
    {
    	return $this->hasMany(Tagihan::class);
    }
}
