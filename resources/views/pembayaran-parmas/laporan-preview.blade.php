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

        h3 {
            margin: auto;
        }

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
            <h2 style="font-family: sans-serif;">Laporan Pembayaran Parmas</h2>
        </center>
        <br>
        <b>Dari tanggal {{ \Carbon\Carbon::parse(request()->tanggal_mulai)->format('d-m-Y') }} -
            {{ \Carbon\Carbon::parse(request()->tanggal_selesai)->format('d-m-Y') }}</b><br><br>
        <table style="" border="1" cellspacing="0" cellpadding="10" width="100%">
            <thead>
                <tr>
                    <th scope="col" style="font-family: sans-serif;">No</th>
                    <th scope="col" style="font-family: sans-serif;">Nama Siswa</th>
                    <th scope="col" style="font-family: sans-serif;">Nisn</th>
                    <th scope="col" style="font-family: sans-serif;">Kelas</th>
                    <th scope="col" style="font-family: sans-serif;">Tanggal Bayar</th>
                    <th scope="col" style="font-family: sans-serif;">Bulan</th>
                    <th scope="col" style="font-family: sans-serif;">Tahun</th>
                    <th scope="col" style="font-family: sans-serif;">Petugas</th>
                    <th scope="col" style="font-family: sans-serif;">Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran as $row)
                    <tr>
                        <th scope="row" style="font-family: sans-serif;">{{ $loop->iteration }}</th>
                        <td style="font-family: sans-serif;">{{ $row->siswa->nama_siswa }}</td>
                        <td style="font-family: sans-serif;">{{ $row->nisn }}</td>
                        <td style="font-family: sans-serif;">{{ $row->siswa->kelas->nama_kelas }}</td>
                        <td style="font-family: sans-serif;">
                            {{ \Carbon\Carbon::parse($row->tanggal_bayar)->format('d-m-Y') }}
                        </td>
                        <td style="font-family: sans-serif;">{{ $row->bulan_bayar }}</td>
                        <td style="font-family: sans-serif;">{{ $row->tahun_bayar }}</td>
                        <td style="font-family: sans-serif;">{{ $row->petugas->nama_petugas }}</td>
                        <td style="font-family: sans-serif;">{{ $row->jumlah_bayar }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
