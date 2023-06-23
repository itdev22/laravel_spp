<!DOCTYPE html>
<html>
<head>
    <title>GENERATE PDF</title>
    <style>
        .container {
            position: relative;
        }

        .logo_sma {
            position: absolute;
            top: 3px;
            /* Ubah nilai top sesuai dengan posisi vertikal yang diinginkan */
            left: 1px;
            /* Ubah nilai left sesuai dengan posisi horizontal yang diinginkan */
            width: 120px;

        }

        .logo_dispen {
            position: absolute;
            top: 5px;
            /* Ubah nilai top sesuai dengan posisi vertikal yang diinginkan */
            right: 5px;
            /* Ubah nilai left sesuai dengan posisi horizontal yang diinginkan */
            width: 100px;

        }

        /* h3 {
            margin: auto;
        } */

        /* h5 {
            padding: 0px;
            margin-top: auto;
        } */
    </style>
</head>

<body>
    <img src="{{ asset('templates/backend/AdminLTE-3.1.0/dist/img/smapa.png') }}" alt="Images" class="logo_dispen">
    <img src="{{ asset('templates/backend/AdminLTE-3.1.0/dist/img/ok.png') }}" alt="Images" class="logo_sma">

    <div class="container">
        <center>
            <h3 style="font-family: sans-serif;">PEMERINTAH PROVINSI JAWA TIMUR <br> <b>DINAS PENDIDIKAN
                    <br>
                    SEKOLAH MENENGAH ATAS NEGERI 4 KOTA KEDIRI</b> </h3>
            <h5 style="">JL. Sersan Suharmaji IX/52, Manisrenggo, Telp/Fax. (0354) 688864,
                KP.64128 Kediri
                <br> Website : http://.sman4-kdr.sch.id Email : sman4.info@gmail.com
            </h5>
            <hr>

            <br>
                <h2 style="font-family: sans-serif;">Laporan Pembayaran Parmas</h2><br><br>
        </center>
        <br>
        <div style="float: left;">
            <b style="font-family: sans-serif;">Nama Siswa : {{ $data_siswa->nama_siswa }}</b><br>
            <b style="font-family: sans-serif;">NISN : {{ $data_siswa->nisn }}</b><br>
            <b style="font-family: sans-serif;">Kelas : {{ $data_siswa->kelas->nama_kelas }}</b><br>
        </div>

<br><br>
<b>Untuk Tahun : {{ request()->tahun_bayar }}</b><br><br>
<table style="" border="1" cellspacing="0" cellpadding="10" width="100%">
  <thead>
    <tr>
      <th style="font-family: sans-serif;">No</th>
      <th style="font-family: sans-serif;">Nama Siswa</th>
      <th style="font-family: sans-serif;">Nisn</th>
      <th style="font-family: sans-serif;">Tanggal Bayar</th>
      <th style="font-family: sans-serif;">Nama Petugas</th>
      <th style="font-family: sans-serif;">Untuk Bulan</th>
      <th style="font-family: sans-serif;">Untuk Tahun</th>
      <th style="font-family: sans-serif;">Nominal</th>
    </tr>
  </thead>
  <tbody>
    @foreach($pembayaran as $row)
    <tr>
      <td style="font-family: sans-serif;">{{ $loop->iteration }}</td>
      <td style="font-family: sans-serif;">{{ $row->siswa->nama_siswa }}</td>
      <td style="font-family: sans-serif;">{{ $row->nisn }}</td>
      <td style="font-family: sans-serif;">{{ \Carbon\Carbon::parse($row->tanggal_bayar)->format('d-m-Y') }}</td>
      <td style="font-family: sans-serif;">{{ $row->petugas->nama_petugas }}</td>
      <td style="font-family: sans-serif;">{{ $row->bulan_bayar }}</td>
      <td style="font-family: sans-serif;">{{ $row->tahun_bayar }}</td>
      <td style="font-family: sans-serif;">{{ $row->jumlah_bayar }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</body>
</html>
