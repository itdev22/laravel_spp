<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PembayaranTagihan;
use App\Models\TagihanSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class MidtrandsController extends Controller
{
    public static function NewTransaction($kode_pembayaran, $nominal)
    {
        $curl = curl_init();

        $params = array(
            'transaction_details' => array(
                'order_id' => $kode_pembayaran,
                'gross_amount' => $nominal,
            )
        );


        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.sandbox.midtrans.com/snap/v1/transactions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic U0ItTWlkLXNlcnZlci1pNjJELVhoa3RIekNha0dLNmx6RS0ydGU6'
            ),
        ));
        // Log::debug($curl);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
        return json_decode($response);
    }


    public function callback()
    {
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        //verify signature

        //update status by order id
        Log::debug($result->order_id);
        Pembayaran::where('kode_pembayaran', $result->order_id)->update(['status' => 'Success']);
        $cekPembayaranTagihan = PembayaranTagihan::where('kode_pembayaran', $result->order_id)->first();
        if ($cekPembayaranTagihan) {
            DB::beginTransaction();
            try {
                $pembayaranTagihan = PembayaranTagihan::where('siswa_id', '=', $cekPembayaranTagihan->siswa_id)
                    ->where('tagihansiswa_id', '=', $cekPembayaranTagihan->tagihansiswa_id)
                    ->get();

                $sum_nominal = 0;
                if ($pembayaranTagihan->count() > 0) {
                    $sum_nominal = $pembayaranTagihan->sum('nominal');
                } else {
                    $sum_nominal = $cekPembayaranTagihan->nominal;
                }


                TagihanSiswa::where('siswa_id', '=', $cekPembayaranTagihan->siswa_id)
                    ->where('id', '=', $cekPembayaranTagihan->tagihansiswa_id)
                    ->update([
                        'nominal' => $sum_nominal,
                        'status' => 'lunas'
                    ]);

                $tagihansiswa = TagihanSiswa::with(['tagihan'])->where('siswa_id', '=', $cekPembayaranTagihan->siswa_id)->where('tagihan_id', '=', $request->tagihansiswa_id)->first();
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

                $cekPembayaranTagihan->status = 'finish';
                $cekPembayaranTagihan->save();

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }

        return response()->json($result);
    }
}
