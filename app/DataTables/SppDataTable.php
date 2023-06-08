<?php

namespace App\DataTables;

use App\Models\Spp;
use DataTables;

class SppDataTable
{
    public function data()
    {
        // $btn = '<div class="row"><a href="javascript:void(0)" id="' . $row->id .
        //     '" class="btn btn-primary btn-sm ml-2 btn-edit">Edit</a>';
        $data = Spp::with('pembayaran')->oldest('tahun');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" id="' . $row->id .
                    '" class="btn btn-danger btn-sm ml-2 btn-delete">Delete</a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
