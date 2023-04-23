<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spp;
use App\DataTables\SiswaPermasDataTable;

class PembayaranParmasController extends Controller
{
    public function index(Request $request, SiswaPermasDataTable $datatable)
    {
        if ($request->ajax()) {
            return $datatable->data();
        }

        // dd($request->ajax());

        return view('siswa.pembayaran-parmas.index');
    }
}
