<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\MidtrandsController;
use App\Models\Kelas;
use App\Models\PembayaranTagihan;
use App\Models\Petugas;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Tagihan;
use App\Models\TagihanSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;


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

        $tagihansiswas = TagihanSiswa::with(['tagihan'])->where('siswa_id', '=', $siswa->id)->where('status', '!=', 'lunas')->get();

        return view('pembayaran-tagihan.bayar', compact(['siswa', 'tagihansiswas']));
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
                        'tanggal_bayar' => Carbon::now('Asia/Jakarta'),
                        'nisn' =>  $request->nisn,
                        'nominal' => $request->dibayar,
                        'status' => 'finish',
                        'metode' => 'offline'
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

                    $tagihansiswa = TagihanSiswa::with(['tagihan'])->where('siswa_id', '=', $request->siswa_id)->where('id', '=', $request->tagihansiswa_id)->first();
                    // dd($tagihansiswa, $request->tagihansiswa_id, $request->siswa_id);
                    $tagihansiswa->nominal = $sum_nominal;
                    if ($tagihansiswa->nominal >= $tagihansiswa->tagihan->nominal) {
                        $tagihansiswa->status = 'lunas';
                    } else if ($tagihansiswa->nominal <= 0) {
                        $tagihansiswa->status = 'belum lunas';
                    } else {
                        $tagihansiswa->status = 'dicicil';
                    }
                    $tagihansiswa->save();
                    DB::commit();
                } catch (\Throwable $th) {
                    throw $th;
                    DB::rollback();
                    // return back()->with('error', 'Pembayaran gagal disimpan!');
                }
            } else if ($request->type_pembayaran == 'Online') {
                DB::beginTransaction();
                try {
                    //code..
                    $kodePembayaran = 'Tagihan' . Str::upper(Str::random(5));
                    $result = MidtrandsController::NewTransaction($kodePembayaran, $request->dibayar);
                    $data = json_decode($result, true);
                    PembayaranTagihan::create([
                        'kode_pembayaran' => $kodePembayaran,
                        'petugas_id' => $petugas->id,
                        'siswa_id' => $request->siswa_id,
                        'tagihansiswa_id' => $request->tagihansiswa_id,
                        'tanggal_bayar' => Carbon::now('Asia/Jakarta'),
                        'nisn' =>  $request->nisn,
                        'nominal' => $request->dibayar,
                        'status' => 'pending',
                        'metode' => 'online',
                        'url_payment' => $data["redirect_url"]
                    ]);



                    // $pembayaranTagihan = PembayaranTagihan::where('siswa_id', '=', $request->siswa_id)
                    //     ->where('tagihansiswa_id', '=', $request->tagihansiswa_id)
                    //     ->get();

                    // $sum_nominal = 0;
                    // if ($pembayaranTagihan->count() > 0) {
                    //     $sum_nominal = $pembayaranTagihan->sum('nominal');
                    // } else {
                    //     $sum_nominal = $request->dibayar;
                    // }


                    // TagihanSiswa::where('siswa_id', '=', $request->siswa_id)
                    //     ->where('id', '=', $request->tagihansiswa_id)
                    //     ->update([
                    //         'nominal' => $sum_nominal,
                    //         'status' => 'lunas'
                    //     ]);

                    // $tagihansiswa = TagihanSiswa::with(['tagihan'])->where('siswa_id', '=', $request->siswa_id)->where('tagihan_id', '=', $request->tagihansiswa_id)->first();
                    // // dd($tagihansiswa, $request->tagihansiswa_id, $request->siswa_id);
                    // $tagihansiswa->nominal = $sum_nominal;
                    // if ($tagihansiswa->nominal >= $tagihansiswa->tagihan->nominal) {
                    //     $tagihansiswa->status = 'lunas';
                    // } else if ($tagihansiswa->nominal <= 0) {
                    //     $tagihansiswa->status = 'belum lunas';
                    // } else {
                    //     $tagihansiswa->status = 'dicicil';
                    // }
                    // $tagihansiswa->save();
                    DB::commit();
                } catch (\Throwable $th) {
                    throw $th;
                    DB::rollback();
                    return back()->with('error', 'Pembayaran gagal disimpan!');
                }
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

    public function printHistoryPembayaran($id)
    {
        $data['pembayaran'] = PembayaranTagihan::with(['petugas', 'siswa', 'tagihansiswa.tagihan'])
            ->where('id', $id)
            ->first();

        $pdf = PDF::loadView('pembayaran-tagihan.history-pembayaran-preview', $data);
        return $pdf->stream();
    }

    public function laporan()
    {
        // $kelas_id = $request->validate([
        //         'kelas_id' => $request->kelas_id,
        // ]);
        $kelass = Kelas::all();
        $tagihans = Tagihan::all();
        return view('pembayaran-tagihan.laporan', compact(['kelass', 'tagihans']));
    }

    public function printPdf(Request $request)
    {
        $tanggal = $request->validate([
            'tanggal_mulai' => '',
            'tanggal_selesai' => '',
        ]);

        $tanggal['tanggal_mulai'] = Carbon::parse($request->tanggal_mulai);
        $tanggal['tanggal_selesai'] = Carbon::parse($request->tanggal_selesai)->endOfDay();
        $q = PembayaranTagihan::with(['petugas', 'siswa', 'tagihansiswa.tagihan']);
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $q->whereBetween('tanggal_bayar', $tanggal);
        }
        if ($request->kelas) {
            $q->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas);
            });
        };
        if ($request->nama_tagihan) {
            $q->whereHas('tagihansiswa', function ($q) use ($request) {
                $q->where('tagihan_id', $request->nama_tagihan);
            });
        };
        if ($request->status_tagihan) {
            $q->whereHas('tagihansiswa', function ($q) use ($request) {
                $q->where('status', $request->status_tagihan);
            });
        }
        $data['pembayaran'] = $q->get();
        //print
        if ($data['pembayaran']->count() > 0) {
            $pdf = PDF::loadView('pembayaran-tagihan.laporan-preview', $data);
            return $pdf->download('pembayaran-tagihan-' .
                Carbon::parse($request->tanggal_mulai)->format('d-m-Y') . '-' .
                Carbon::parse($request->tanggal_selesai)->format('d-m-Y') .
                Str::random(9) . '.pdf');
        } else {
            return back()->with('error', 'Data pembayaran TAGIHAN tanggal ' .
                Carbon::parse($request->tanggal_mulai)->format('d-m-Y') . ' sampai dengan ' .
                Carbon::parse($request->tanggal_selesai)->format('d-m-Y') . ' Tidak Tersedia');
        }
    }
}
