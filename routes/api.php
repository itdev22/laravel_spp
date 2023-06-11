<?php

use App\Http\Controllers\Payment\MidtrandsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('midtrands')->group(function () {
    Route::post('/new-transaction', [MidtrandsController::class, 'NewTransaction']);
    Route::post('/callback', [MidtrandsController::class, 'callback']);
});

Route::any('pull', function () {
    exec('cd /home/rtrsite.com/web/dina.rtrsite.com/public_html/laravel_spp && git pull');
});
