<?php

namespace App\Http\Controllers\Pembayaran;

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
use App\Helpers\Universe;
use App\Http\Controllers\Controller;
use PDF;
use DataTables;
use App\Http\Controllers\Payment\MidtrandsController;


class ParmasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Siswa::with(['kelas'])->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('parmas.pembayaran.bayar', $row->nisn) . '"class="btn btn-primary btn-sm ml-2">
                    <i class="fas fa-money-check"></i> BAYAR
                    </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran-parmas.index');
    }

    public function bayar($nisn)
    {
        $siswa = Siswa::with(['kelas'])
            ->where('nisn', $nisn)
            ->first();

        $spp = Spp::all();

        return view('pembayaran-parmas.bayar', compact('siswa', 'spp'));
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
            if ($request->metode_pembayaran == 'offline') {

                DB::transaction(function () use ($request, $petugas) {
                    foreach ($request->bulan_bayar as $bulan) {
                        Pembayaran::create([
                            'kode_pembayaran' => 'PARMAS' . Str::upper(Str::random(5)),
                            'petugas_id' => $petugas->id,
                            'siswa_id' => $request->siswa_id,
                            'nisn' => $request->nisn,
                            'tanggal_bayar' => Carbon::now('Asia/Jakarta'),
                            'tahun_bayar' => $request->tahun_bayar,
                            'bulan_bayar' => $bulan,
                            'jumlah_bayar' => $request->jumlah_bayar,
                            'metode' => 'offline',
                            'status' => 'finish'
                        ]);
                    }
                });

                return redirect()->route('parmas.pembayaran.history-pembayaran')
                    ->with('success', 'Pembayaran berhasil disimpan!');
            }

            if ($request->metode_pembayaran == 'online') {
                foreach ($request->bulan_bayar as $bulan) {
                    $kodePembayaran = 'PARMAS' . Str::upper(Str::random(5));
                    $result = MidtrandsController::NewTransaction($kodePembayaran, $request->jumlah_bayar);
                    $data = json_decode($result, true);
                    Pembayaran::create([
                        'kode_pembayaran' => $kodePembayaran,
                        'petugas_id' => $petugas->id,
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
                return redirect()->route('parmas.pembayaran.history-pembayaran')
                    ->with('success', 'Pembayaran berhasil disimpan!');
            }
        } else {
            return back()
                ->with('error', 'Siswa Dengan Nama : ' . $request->nama_siswa . ' , NISN : ' .
                    $request->nisn . ' Sudah Membayar PARTISIPASI MASYARAKAT di bulan yang diinput (' .
                    implode($pembayaran, ',') . ")" . ' , di Tahun : ' . $request->tahun_bayar . ' , Pembayaran Dibatalkan');
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
                    $btn = '<div class="row"><a href="' . route('parmas.pembayaran.status-pembayaran.show', $row->nisn) .
                        '"class="btn btn-primary btn-sm">DETAIL</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran-parmas.status-pembayaran');
    }

    public function statusPembayaranShow(Siswa $siswa)
    {
        $spp = Spp::all();
        return view('pembayaran-parmas.status-pembayaran-tahun', compact('siswa', 'spp'));
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

        return view('pembayaran-parmas.status-pembayaran-show', compact('siswa', 'spp', 'pembayaran'));
    }

    public function historyPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::with(['petugas', 'siswa' => function ($query) {
                $query->with('kelas');
            }])->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="row"><a href="' . route('parmas.pembayaran.history-pembayaran.print', $row->id) . '"class="btn btn-danger btn-sm ml-2" target="_blank">
                    <i class="fas fa-print fa-fw"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran-parmas.history-pembayaran');
    }

    public function printHistoryPembayaran($id)
    {
        $data['pembayaran'] = Pembayaran::with(['petugas', 'siswa'])
            ->where('id', $id)
            ->first();

        $pdf = PDF::loadView('pembayaran-parmas.history-pembayaran-preview', $data);
        return $pdf->stream();
    }

    public function laporan()
    {
        // $kelas_id = $request->validate([
        //         'kelas_id' => $request->kelas_id,
        // ]);
        $kelass = Kelas::all();
        return view('pembayaran-parmas.laporan', compact(['kelass']));
    }

    public function printPdf(Request $request)
    {
        $tanggal = $request->validate([
            'tanggal_mulai' => '',
            'tanggal_selesai' => '',
        ]);

        $tanggal['tanggal_mulai'] = Carbon::parse($request->tanggal_mulai);
        $tanggal['tanggal_selesai'] = Carbon::parse($request->tanggal_selesai)->endOfDay();
        $q = Pembayaran::with(['petugas', 'siswa']);
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $q->whereBetween('tanggal_bayar', $tanggal);
        }
        if ($request->kelas != '') {
            $q->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas);
            });
        };
        if ($request->tahun != '') {
            $q->where('tahun_bayar', $request->tahun);
        };
        if ($request->bulan != '') {
            $q->where('bulan_bayar', $request->bulan);
        };
        $data['pembayaran'] =  $q->get();

        //print
        if ($data['pembayaran']->count() > 0) {
            // return view('pembayaran-parmas.laporan-preview', $data);
            $pdf = PDF::loadView('pembayaran-parmas.laporan-preview', $data);
            // return $pdf->stream();
            return $pdf->download('pembayaran-parmas-' .
                Carbon::parse($request->tanggal_mulai)->format('d-m-Y') . '-' .
                Carbon::parse($request->tanggal_selesai)->format('d-m-Y') .
                Str::random(9) . '.pdf');
        } else {
            return back()->with('error', 'Data pembayaran PARMAS tanggal ' .
                Carbon::parse($request->tanggal_mulai)->format('d-m-Y') . ' sampai dengan ' .
                Carbon::parse($request->tanggal_selesai)->format('d-m-Y') . ' Tahun ' . $request->tahun . ' Tidak Tersedia');
        }
    }
}
