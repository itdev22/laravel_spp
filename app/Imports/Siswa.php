<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Siswa as SiswaModel;

class Siswa implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if ($key == 0) continue; // Skip the header row

            // Insert ke tabel users
            $user = User::create([
                'username' => Str::lower($row[0]), // Sesuaikan dengan nomor kolom untuk Username (misalnya, kolom kedua)
                'password' => Hash::make($row[1]),
            ]);

            $user->assignRole('siswa'); // Atur role sesuai dengan peran siswa

            // Insert ke tabel siswa
            SiswaModel::create([
                'user_id' => $user->id,
                'kode_siswa' => 'SSWR' . Str::upper(Str::random(3)),
                'nisn' => $row[2], // Sesuaikan dengan nomor kolom untuk NISN (misalnya, kolom keempat)
                'nis' => $row[3], // Sesuaikan dengan nomor kolom untuk NIS (misalnya, kolom ketiga)
                'nama_siswa' => $row[4], // Sesuaikan dengan nomor kolom untuk Nama Siswa (misalnya, kolom pertama)
                'jenis_kelamin' => $row[5], // Sesuaikan dengan nomor kolom untuk Jenis Kelamin (misalnya, kolom kelima)
                'alamat' => $row[6], // Sesuaikan dengan nomor kolom untuk Alamat (misalnya, kolom keenam)
                'no_telepon' => $row[7], // Sesuaikan dengan nomor kolom untuk No Telepon (misalnya, kolom ketujuh)
                'kelas_id' => $row[8], // Sesuaikan dengan nomor kolom untuk Kelas ID (misalnya, kolom kedelapan)
            ]);
        }
    }
}
