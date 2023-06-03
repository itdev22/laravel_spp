<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\PembayaranTagihan;
use App\Models\Petugas;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Tagihan;
use App\Models\TagihanSiswa;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TagihanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tagihan::with([])->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('tagihan.pembayaran.detailtagihan', $row->id) . '"class="btn btn-primary btn-sm ml-2">
                <i class="fas fa-money-check"></i> Detail Siswa
                </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran-tagihan.index');
    }

    public function detailsiswa(Request $request, $tagihanid)
    {
        if ($request->ajax()) {
            $data = TagihanSiswa::with(['siswa', 'tagihan'])->where('tagihan_id', $tagihanid)->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('tagihan.pembayaran.bayar', $row->siswa->nisn) . '"class="btn btn-primary btn-sm ml-2">
                <i class="fas fa-money-check"></i> Bayar
                </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran-tagihan.detail-siswa');
    }

    public function bayar($nisn)
    {
        $siswa = Siswa::with(['kelas'])
            ->where('nisn', $nisn)
            ->first();

        $tagihansiswas = TagihanSiswa::with(['tagihan'])->where('siswa_id', '=', $siswa->id)->get();

        return view('pembayaran-tagihan.bayar', compact(['siswa', 'tagihansiswas']));
    }

    public function tagihan($id)
    {
        $tagihan = Tagihan::where('id', $id)
            ->first();

        return response()->json([
            'data' => $tagihan,
            'nominal_rupiah' => 'Rp ' . number_format($tagihan->nominal, 0, 2, '.'),
        ]);
    }

    public function prosesBayar(Request $request, $nisn)
    {
        $request->validate(
            [
                'jumlah_bayar' => 'required',
            ],
            [
                'jumlah_bayar.required' => 'Jumlah bayar tidak boleh kosong!'
            ]
        );

        $petugas = Petugas::where('user_id', Auth::user()->id)
            ->first();

        $pembayaran = PembayaranTagihan::where('siswa_id', '=', $request->siswa_id)
            ->where('tagihansiswa_id', '=', $request->tagihansiswa_id)
            ->where('status', '=', 'pending')
            ->get();

        if ($pembayaran->count() == 0) {
            if ($request->type_pembayaran == 'Offline') {
                DB::beginTransaction();
                try {
                    //code..
                    PembayaranTagihan::create([
                        'kode_pembayaran' => 'Tagihan' . Str::upper(Str::random(5)),
                        'petugas_id' => $petugas->id,
                        'siswa_id' => $request->siswa_id,
                        'tagihansiswa_id' => $request->tagihansiswa_id,
                        'nisn' =>  $request->nisn,
                        'nominal' => $request->dibayar,
                        'status' => 'finish'
                    ]);

                    $pembayaranTagihan = PembayaranTagihan::where('siswa_id', '=', $request->siswa_id)
                        ->where('tagihansiswa_id', '=', $request->tagihansiswa_id)
                        ->get();

                    $sum_nominal = 0;
                    if ($pembayaranTagihan->count() > 0) {
                        $sum_nominal = $pembayaranTagihan->sum('nominal');
                    } else {
                        $sum_nominal = $request->dibayar;
                    }


                    TagihanSiswa::where('siswa_id', '=', $request->siswa_id)
                        ->where('id', '=', $request->tagihansiswa_id)
                        ->update([
                            'nominal' => $sum_nominal,
                            'status' => 'lunas'
                        ]);

                    $tagihansiswa = TagihanSiswa::with(['tagihan'])->where('siswa_id', '=', $request->siswa_id)->where('tagihan_id', '=', $request->tagihansiswa_id)->first();
                    // dd($tagihansiswa, $request->tagihansiswa_id, $request->siswa_id);
                    $tagihansiswa->nominal = $sum_nominal;
                    if ($tagihansiswa->nominal >= $tagihansiswa->tagihan->nominal) {
                        $tagihansiswa->status = 'lunas';
                    } else {
                        $tagihansiswa->status = 'belum lunas';
                    }
                    $tagihansiswa->save();
                    DB::commit();
                } catch (\Throwable $th) {
                    throw $th;
                    DB::rollback();
                    return back()->with('error', 'Pembayaran gagal disimpan!');
                }
            } else if ($request->type_pembayaran == 'Online') {
                DB::transaction(function () use ($request, $petugas) {
                    PembayaranTagihan::create([
                        'kode_pembayaran' => 'Tagihan' . $request->siswa_id . $request->tagihan_id . $request->nisn,
                        'petugas_id' => $petugas->id,
                        'siswa_id' => $request->siswa_id,
                        'tagihansiswa_id' => $request->tagihan_id,
                        'nisn' =>  $request->nisn,
                    ]);
                });
            }

            return redirect()->route('tagihan.pembayaran.history-pembayaran')
                ->with('success', 'Pembayaran berhasil disimpan!');
        } else {
            return back()->with('error', 'Pembayaran gagal disimpan!');
        }
    }

    public function historyPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $data = PembayaranTagihan::with(['petugas', 'siswa.kelas'])
                ->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('tagihan.pembayaran.history-pembayaran.print', $row->id) . '"class="btn btn-danger btn-sm ml-2" target="_blank">
                    <i class="fas fa-print fa-fw"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran-tagihan.history-pembayaran');
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
                    $btn = '<div class="row"><a href="' . route('tagihan.pembayaran.status-pembayaran.show', $row->nisn) .
                        '"class="btn btn-primary btn-sm">DETAIL</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran-tagihan.status-pembayaran');
    }

    public function statusPembayaranShow(Siswa $siswa)
    {
        $spp = TagihanSiswa::with(['tagihan'])->where('siswa_id', $siswa->id)->get();
        return view('pembayaran-tagihan.status-pembayaran-tahun', compact('siswa', 'spp'));
    }
}