<?php

namespace App\Http\Controllers;

use App\Helpers\Universe;
use App\Http\Controllers\Payment\MidtrandsController;
use App\Models\Pembayaran;
use App\Models\Petugas;
use App\Models\Siswa;
use App\Models\Spp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DataTables;
use PDF;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranPermasController extends Controller
{
    public function bayar($nisn)
    {
        $siswa = Siswa::with(['kelas'])
            ->where('nisn', $nisn)
            ->first();

        $spp = Spp::all();

        return view('siswa.pembayaran.permas.pembayaran.bayar', compact('siswa', 'spp'));
    }

    public function prosesBayar(Request $request, $nisn)
    {
        $request->validate([
            'jumlah_bayar' => 'required',
        ], [
            'jumlah_bayar.required' => 'Jumlah bayar tidak boleh kosong!'
        ]);

        $petugas = Petugas::where('user_id', Auth::user()->id)
            ->first();

        $pembayaran = Pembayaran::whereIn('bulan_bayar', $request->bulan_bayar)
            ->where('tahun_bayar', $request->tahun_bayar)
            ->where('siswa_id', $request->siswa_id)
            ->pluck('bulan_bayar')
            ->toArray();

        if (!$pembayaran) {
            if ($request->metode_pembayaran == 'online') {
                foreach ($request->bulan_bayar as $bulan) {
                    $kodePembayaran = 'PARMAS' . Str::upper(Str::random(5));
                    $result = MidtrandsController::NewTransaction($kodePembayaran, $request->jumlah_bayar);
                    $data = json_decode($result, true);
                    Pembayaran::create([
                        'kode_pembayaran' => $kodePembayaran,
                        'petugas_id' => 1,
                        'siswa_id' => $request->siswa_id,
                        'nisn' => $request->nisn,
                        'tanggal_bayar' => Carbon::now('Asia/Jakarta'),
                        'tahun_bayar' => $request->tahun_bayar,
                        'bulan_bayar' => $bulan,
                        'jumlah_bayar' => $request->jumlah_bayar,
                        'metode' => 'online',
                        'status' => 'pending',
                        'url_payment' => $data["redirect_url"]
                    ]);
                }
                return back()
                    ->with('success', 'Pembayaran berhasil disimpan!');
            }
        } else {
            return back()
                ->with('error', 'Siswa Dengan Nama : ' . $request->nama_siswa . ' , NISN : ' .
                    $request->nisn . ' Sudah Membayar PARTISIPASI MASYARAKAT di bulan yang diinput (' .
                    implode($pembayaran, ',') . ")" . ' , di Tahun : ' . $request->tahun_bayar . ' , Pembayaran Dibatalkan');
        }
    }

    public function spp($tahun, $nisn)
    {
        $spp = Spp::where('tahun', $tahun)
            ->first();


        $bulans = Universe::bulanAll();

        $bulan_bayar = '';
        foreach ($bulans as $key => $bulan) {
            if (!Pembayaran::where('bulan_bayar', $bulan['nama_bulan'])->where('tahun_bayar', $tahun)->where('nisn', $nisn)->first()) {
                $bulan_bayar .= '<option value="' . $bulan['nama_bulan'] . '">' . $bulan['nama_bulan'] . '</option>';
            }
        }

        // $bulan_bayar = 'asd';
        return response()->json([
            'data' => $spp,
            'nominal_rupiah' => 'Rp ' . number_format($spp->nominal, 0, 2, '.'),
            'bulan_bayar' => $bulan_bayar
        ]);
    }

    public function pembayaran()
    {
        $spp = Spp::all();
        $user = Siswa::where('user_id', Auth::user()->id)->first();
        return view('siswa.pembayaran.permas.pembayaran.index', compact(['spp', 'user']));
    }

    public function pembayaranShow(Spp $spp)
    {
        $siswa = Siswa::where('user_id', Auth::user()->id)
            ->first();

        $pembayaran = Pembayaran::with(['petugas', 'siswa'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_bayar', $spp->tahun)
            ->oldest()
            ->get();
        return view('siswa.pembayaran.permas.pembayaran.show', compact('pembayaran', 'siswa', 'spp'));
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            // dd('asd');
            $siswa = Siswa::where('user_id', Auth::user()->id)
                ->first();

            $data = Pembayaran::with(['petugas', 'siswa' => function ($query) {
                $query->with(['kelas']);
            }])
                ->where('siswa_id', $siswa->id)
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('siswa.history-permas.show', $row->id) . '"class="btn btn-danger btn-sm ml-2" target="_blank">
                    <i class="fas fa-print fa-fw"></i>
                    </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('siswa.pembayaran.permas.history.index');
    }

    public function historyShow($id)
    {
        $data['siswa'] = Siswa::where('user_id', Auth::user()->id)
            ->first();

        $data['pembayaran'] = Pembayaran::with(['petugas', 'siswa'])
            ->where('id', $id)
            ->where('siswa_id', $data['siswa']->id)
            ->first();

        $pdf = PDF::loadView('siswa.history-permas.show', $data);
        return $pdf->stream();
    }

    public function laporan()
    {
        $spp = Spp::all();
        return view('siswa.pembayaran.permas.laporan.index', compact('spp'));
    }

    public function laporanshow(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::user()->id)
            ->first();

        $data['pembayaran'] = Pembayaran::with(['petugas', 'siswa'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_bayar', $request->tahun_bayar)
            ->get();

        $data['data_siswa'] = $siswa;

        if ($data['pembayaran']->count() > 0) {
            $pdf = PDF::loadView('siswa.pembayaran.permas.laporan.show', $data);
            return $pdf->download('pembayaran-parmas-' . $siswa->nama_siswa . '-' .
                $siswa->nisn . '-' .
                $request->tahun_bayar . '-' .
                Str::random(9) . '.pdf');
        } else {
            return back()->with('error', 'Data Pembayaran PARMAS Anda Tahun ' . $request->tahun_bayar . ' tidak tersedia');
        }
    }
}
