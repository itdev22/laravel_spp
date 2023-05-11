<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtrandsController extends Controller
{
    public function NewTransaction()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.sandbox.midtrans.com/snap/v1/transactions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
          "transaction_details": {
            "order_id": "pembayaran-spp2",
            "gross_amount": 10000
          }
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic U0ItTWlkLXNlcnZlci1pNjJELVhoa3RIekNha0dLNmx6RS0ydGU6'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        return response()->json($response);
    }


    public function callback()
    {
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        //verify signature

        //update status by order id
        Log::debug($result->order_id);
        Pembayaran::where('order_id', $result->order_id)->update(['status' => 'Success']);

        return response()->json($result);
    }
}
