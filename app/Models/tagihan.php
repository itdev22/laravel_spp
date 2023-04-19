<?php

namespace App\Models;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tagihan extends Model
{
    use HasFactory;
    protected $table = 'tagihan';

    protected $fillable = [
    	'nama_tagihan',
    	'nominal',
        'max_angsuran',
        'kelas_id',
        'create_at',
        'update_at',
    ];

    public function siswa()
    {
    	return $this->hasMany(Siswa::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
