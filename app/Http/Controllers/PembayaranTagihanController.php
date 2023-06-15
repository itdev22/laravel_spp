<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Petugas;
use App\Models\Kelas;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Validator;
use App\Helpers\Bulan;
use App\Http\Controllers\Payment\MidtrandsController;
use App\Models\PembayaranTagihan;
use App\Models\Tagihan;
use App\Models\TagihanSiswa;
use PDF;
use DataTables;

class PembayaranTagihanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Siswa::with(['kelas'])->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('pembayaran.bayar', $row->nisn) . '"class="btn btn-primary btn-sm ml-2">
                    <i class="fas fa-money-check"></i> BAYAR
                    </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('siswa.pembayaran.tagihan.pembayaran.index');
    }

    public function bayar($nisn)
    {
        $siswa = Siswa::with(['kelas'])
            ->where('nisn', $nisn)
            ->first();

        $tagihansiswas = TagihanSiswa::with(['tagihan'])->where('siswa_id', '=', $siswa->id)->where('status', '!=', 'lunas')->get();

        return view('siswa.pembayaran.tagihan.pembayaran.bayar', compact(['siswa', 'tagihansiswas']));
    }

    public function spp($tahun)
    {
        $spp = Spp::where('tahun', $tahun)
            ->first();

        return response()->json([
            'data' => $spp,
            'nominal_rupiah' => 'Rp ' . number_format($spp->nominal, 0, 2, '.'),
        ]);
    }

    public function tagihan($id, $idsiswa)
    {
        $tagihanSiswa = TagihanSiswa::where('id', $id)->where('siswa_id', $idsiswa)->first();
        $tagihan = Tagihan::where('id', $tagihanSiswa->tagihan_id)
            ->first();


        return response()->json([
            'data' => $tagihan,
            'data_tagihansiswa' => $tagihanSiswa,
            'nominal_rupiah' => 'Rp ' . number_format($tagihan->nominal, 0, 2, '.'),
        ]);
    }

    public function listPembayaranTagihan($nisn, $tagihansiswa_id)
    {
        // return response()->json($tagihansiswa_id);
        $data = PembayaranTagihan::where('nisn', strval($nisn))->where('tagihansiswa_id', $tagihansiswa_id);
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function prosesBayar(Request $request, $nisn)
    {
        $request->validate([
            'jumlah_bayar' => 'required',
        ], [
            'jumlah_bayar.required' => 'Jumlah bayar tidak boleh kosong!'
        ]);

        $pembayaran = PembayaranTagihan::where('siswa_id', '=', $request->siswa_id)
            ->where('tagihansiswa_id', '=', $request->tagihansiswa_id)
            ->where('status', '=', 'pending')
            ->get();

        // dd(123);
        if ($pembayaran->count() == 0) {
            if ($request->metode_pembayaran == 'Online') {
                DB::beginTransaction();
                try {
                    //code..
                    $kodePembayaran = 'Tagihan' . Str::upper(Str::random(5));
                    $result = MidtrandsController::NewTransaction($kodePembayaran, $request->dibayar);
                    $data = json_decode($result, true);
                    PembayaranTagihan::create([
                        'kode_pembayaran' => $kodePembayaran,
                        'petugas_id' => 1,
                        'siswa_id' => $request->siswa_id,
                        'tagihansiswa_id' => $request->tagihansiswa_id,
                        'tanggal_bayar' => Carbon::now('Asia/Jakarta'),
                        'nisn' =>  $request->nisn,
                        'nominal' => $request->dibayar,
                        'status' => 'pending',
                        'metode' => 'online',
                        'url_payment' => $data["redirect_url"]
                    ]);

                    DB::commit();
                } catch (\Throwable $th) {
                    throw $th;
                    DB::rollback();
                    return back()->with('error', 'Pembayaran gagal disimpan!');
                }
            }

            return back()
                ->with('success', 'Pembayaran berhasil disimpan!');
        } else {
            return back()->with('error', 'Pembayaran gagal disimpan!');
        }
    }

    public function statusPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $data = Siswa::with(['kelas'])
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('pembayaran.status-pembayaran.show', $row->nisn) .
                        '"class="btn btn-primary btn-sm">DETAIL</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran.status-pembayaran');
    }

    public function statusPembayaranShow(Siswa $siswa)
    {
        $spp = Spp::all();
        return view('pembayaran.status-pembayaran-tahun', compact('siswa', 'spp'));
    }

    public function statusPembayaranShowStatus($nisn, $tahun)
    {
        $siswa = Siswa::where('nisn', $nisn)
            ->first();

        $spp = Spp::where('tahun', $tahun)
            ->first();

        $pembayaran = Pembayaran::with(['siswa'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_bayar', $spp->tahun)
            ->get();

        return view('pembayaran.status-pembayaran-show', compact('siswa', 'spp', 'pembayaran'));
    }


    public function historyPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::with(['petugas', 'siswa' => function ($query) {
                $query->with('kelas');
            }])
                ->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('pembayaran.history-pembayaran.print', $row->id) . '"class="btn btn-danger btn-sm ml-2" target="_blank">
                    <i class="fas fa-print fa-fw"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran.history-pembayaran');
    }

    public function printHistoryPembayaran($id)
    {
        $data['pembayaran'] = Pembayaran::with(['petugas', 'siswa'])
            ->where('id', $id)
            ->first();

        $pdf = PDF::loadView('pembayaran.history-pembayaran-preview', $data);
        return $pdf->stream();
    }

    public function laporan()
    {
        // $kelas_id = $request->validate([
        //         'kelas_id' => $request->kelas_id,
        // ]);
        return view('pembayaran.laporan');
    }

    public function printPdf(Request $request)
    {
        $tanggal = $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ]);

        $data['pembayaran'] = Pembayaran::with(['petugas', 'siswa'])
            ->whereBetween('tanggal_bayar', $tanggal)->get();
        //print
        if ($data['pembayaran']->count() > 0) {
            $pdf = PDF::loadView('pembayaran.laporan-preview', $data);
            return $pdf->download('pembayaran-parmas-' .
                Carbon::parse($request->tanggal_mulai)->format('d-m-Y') . '-' .
                Carbon::parse($request->tanggal_selesai)->format('d-m-Y') .
                Str::random(9) . '.pdf');
        } else {
            return back()->with('error', 'Data pembayaran PARMAS tanggal ' .
                Carbon::parse($request->tanggal_mulai)->format('d-m-Y') . ' sampai dengan ' .
                Carbon::parse($request->tanggal_selesai)->format('d-m-Y') . ' Tidak Tersedia');
        }
    }

    public function pembayaran(Request $request)
    {
        $spp = Spp::all();
        if ($request->ajax()) {
            $siswa = Siswa::where('user_id', Auth::user()->id)->first();
            $data = TagihanSiswa::with(['tagihan'])->where('siswa_id', $siswa->id)->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($siswa) {
                    $btn = '<div class="row"><a href="' . route('siswa.pembayaran-tagihan.bayar', $siswa->nisn) . '"class="btn btn-primary btn-sm ml-2">
                <i class="fas fa-money-check"></i> Bayar
                </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('siswa.pembayaran.tagihan.pembayaran.index', compact('spp'));
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
        return view('siswa.pembayaran.tagihan.pembayaran.show', compact('pembayaran', 'siswa', 'spp'));
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $siswa = Siswa::where('user_id', Auth::user()->id)
                ->first();

            $data = PembayaranTagihan::with(['tagihansiswa.tagihan', 'petugas', 'siswa' => function ($query) {
                $query->with(['kelas']);
            }])
                ->where('siswa_id', $siswa->id)
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('siswa.history-tagihan.show', $row->id) . '"class="btn btn-danger btn-sm ml-2" target="_blank">
                    <i class="fas fa-print fa-fw"></i>
                    </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('siswa.pembayaran.tagihan.history.index');
    }

    public function historyShow($id)
    {
        $data['siswa'] = Siswa::where('user_id', Auth::user()->id)
            ->first();

        $data['pembayaran'] = Pembayaran::with(['petugas', 'siswa'])
            ->where('id', $id)
            ->where('siswa_id', $data['siswa']->id)
            ->first();

        $pdf = PDF::loadView('siswa.history-tagihan.show', $data);
        return $pdf->stream();
    }

    public function laporanPembayaran()
    {
        $siswa = Siswa::where('user_id', Auth::user()->id)->first();
        $spp = TagihanSiswa::with(['tagihan'])->where('siswa_id', $siswa->id)->get();
        return view('siswa.pembayaran.tagihan.laporan.index', compact('spp'));
    }

    public function laporanshow(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::user()->id)
            ->first();

        $data['pembayaran'] = PembayaranTagihan::with(['petugas', 'siswa', 'tagihansiswa.tagihan'])
            ->where('siswa_id', $siswa->id)
            ->whereHas('tagihansiswa', function ($q) use ($request) {
                $q->where('id', $request->tagihan_id);
            })
            ->get();

        $data['data_siswa'] = $siswa;

        if ($data['pembayaran']->count() > 0) {
            $pdf = PDF::loadView('siswa.pembayaran.tagihan.laporan.show', $data);
            return $pdf->download('pembayaran-tagihan-' . $siswa->nama_siswa . '-' .
                $siswa->nisn . '-' .
                $request->tahun_bayar . '-' .
                Str::random(9) . '.pdf');
        } else {
            return back()->with('error', 'Data Pembayaran Tagihan Anda Tahun ' . $request->tahun_bayar . ' tidak tersedia');
        }
    }
}
