<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use Illuminate\Support\Facades\Auth;
use DataTables;
use PDF;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PembayaranPermasController extends Controller
{
    public function pembayaran()
    {
        $spp = Spp::all();
        return view('siswa.pembayaran.permas.pembayaran.index', compact('spp'));
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
            $pdf = PDF::loadView('siswa.permas.laporan.show', $data);
            return $pdf->download('pembayaran-parmas-' . $siswa->nama_siswa . '-' .
                $siswa->nisn . '-' .
                $request->tahun_bayar . '-' .
                Str::random(9) . '.pdf');
        } else {
            return back()->with('error', 'Data Pembayaran PARMAS Anda Tahun ' . $request->tahun_bayar . ' tidak tersedia');
        }
    }
}
