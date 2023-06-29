<?php

namespace App\DataTables;

use App\Models\Tagihan;
use DataTables;

class TagihanDataTable
{
    public function data()
    {
        $data = Tagihan::with(['kelas', 'tagihan_siswa'])->get();
        // $data = Tagihan::oldest('nama_tagihan');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div class="row"><a href="javascript:void(0)" id="' . $row->id .
                    '" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
                $btn .= '<a href="javascript:void(0)" id="' . $row->id .
                    '" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
