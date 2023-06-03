<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\tagihan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\DataTables\TagihanDataTable;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-tagihan'])->only(['index', 'show']);
        $this->middleware(['permission:create-tagihan'])->only(['create', 'store']);
        $this->middleware(['permission:update-tagihan'])->only(['edit', 'update']);
        $this->middleware(['permission:delete-tagihan'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TagihanDataTable $datatable)
    {
        if ($request->ajax()) {
            return $datatable->data();
        }

        $kelas = Kelas::all();
        // $tagihan = Tagihan::all();

        return view('admin.tagihan.index', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_tagihan' => ['required', 'unique:tagihan'],
            'nominal' => ['required'],
            'kelas_id' => 'required'

        ]);

        if ($validator->passes()) {

            DB::beginTransaction();
            try {
                $tagihan = Tagihan::create([
                    'nama_tagihan' => $request->nama_tagihan,
                    'nominal' => $request->nominal,
                    'kelas_id' => $request->kelas_id,
                ]);

                $siswas = Siswa::where('kelas_id', $request->kelas_id)->get();
                foreach ($siswas as $key => $siswa) {
                    TagihanSiswa::create([
                        'siswa_id' => $siswa->id,
                        'tagihan_id' => $tagihan->id,
                        'nominal' => 0,
                        'status' => 'belum lunas',
                    ]);
                }

                DB::commit();
            } catch (\Throwable $th) {
                // throw $th;
                DB::rollBack();
                return response()->json(['message' => 'Data Gagal disimpan!' . $th]);
            }


            return response()->json(['message' => 'Data berhasil disimpan!']);
        }

        return response()->json(['error' => $validator->errors()->all()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tagihan = Tagihan::with(['kelas'])->findOrFail($id);
        return response()->json(['data' => $tagihan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_tagihan' => ['required'],
            'nominal' => ['required'],
            'max_angsuran' => ['required'],
        ]);

        if ($validator->passes()) {
            // Tagihan::findOrFail($id)->update($request->all());
            Tagihan::findOrFail($id)->update([
                'nama_tagihan' => $request->nama_tagihan,
                'nominal' => $request->nominal,
                'max_angsuran' => $request->max_angsuran,
                'kelas_id' => $request->kelas_id,
            ]);
            return response()->json(['message' => 'Data berhasil diupdate!']);
        }

        return response()->json(['error' => $validator->errors()->all()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tagihan::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
}
