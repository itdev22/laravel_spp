<?php

use App\Http\Controllers\Pembayaran\ParmasController;
use App\Http\Controllers\Pembayaran\TagihanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PembayaranPermasController;
use App\Http\Controllers\PembayaranTagihanController;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware(['guest']);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home.index');
});

Route::prefix('pembayaran')->middleware(['auth', 'role:admin|petugas'])->group(function () {
    // Route::get('bayar', 'PembayaranController@index')->name('pembayaran.index');
    // Route::get('bayar/{nisn}', 'PembayaranController@bayar')->name('pembayaran.bayar');
    // Route::get('spp/{tahun}', 'PembayaranController@spp')->name('pembayaran.spp');
    // Route::post('bayar/{nisn}', 'PembayaranController@prosesBayar')->name('pembayaran.proses-bayar');
    // Route::get('status-pembayaran', 'PembayaranController@statusPembayaran')
    //     ->name('pembayaran.status-pembayaran');

    // Route::get('status-pembayaran/{siswa:nisn}', 'PembayaranController@statusPembayaranShow')
    //     ->name('pembayaran.status-pembayaran.show');

    // Route::get('status-pembayaran/{nisn}/{tahun}', 'PembayaranController@statusPembayaranShowStatus')
    //     ->name('pembayaran.status-pembayaran.show-status');

    // Route::get('history-pembayaran', 'PembayaranController@historyPembayaran')
    //     ->name('pembayaran.history-pembayaran');

    // Route::get('history-pembayaran/preview/{id}', 'PembayaranController@printHistoryPembayaran')
    //     ->name('pembayaran.history-pembayaran.print');

    // Route::get('laporan', 'PembayaranController@laporan')->name('pembayaran.laporan');
    // Route::post('laporan', 'PembayaranController@printPdf')->name('pembayaran.print-pdf');

    Route::group(['prefix' => 'parmas', 'as' => 'parmas.'], function () {
        Route::get('bayar', [ParmasController::class, 'index'])->name('pembayaran.index');
        Route::get('bayar/{nisn}', [ParmasController::class, 'bayar'])->name('pembayaran.bayar');
        Route::get('spp/{tahun}/{nisn}', [ParmasController::class, 'spp'])->name('pembayaran.spp');
        Route::post('bayar/{nisn}', [ParmasController::class, 'prosesBayar'])->name('pembayaran.proses-bayar');
        Route::get('status-pembayaran', [ParmasController::class, 'statusPembayaran'])
            ->name('pembayaran.status-pembayaran');

        Route::get('status-pembayaran/{siswa:nisn}', [ParmasController::class, 'statusPembayaranShow'])
            ->name('pembayaran.status-pembayaran.show');

        Route::get('status-pembayaran/{nisn}/{tahun}', [ParmasController::class, 'statusPembayaranShowStatus'])
            ->name('pembayaran.status-pembayaran.show-status');

        Route::get('history-pembayaran', [ParmasController::class, 'historyPembayaran'])
            ->name('pembayaran.history-pembayaran');

        Route::get('history-pembayaran/preview/{id}', [ParmasController::class, 'printHistoryPembayaran'])
            ->name('pembayaran.history-pembayaran.print');

        Route::get('laporan', [ParmasController::class, 'laporan'])->name('pembayaran.laporan');
        Route::post('laporan', [ParmasController::class, 'printPdf'])->name('pembayaran.print-pdf');
    });

    Route::group(['prefix' => 'tagihan', 'as' => 'tagihan.'], function () {
        Route::get('bayar', [TagihanController::class, 'index'])->name('pembayaran.index');
        Route::get('detailtagihan/{tagihanid}', [TagihanController::class, 'detailsiswa'])->name('pembayaran.detailtagihan');
        Route::get('bayar/{nisn}', [TagihanController::class, 'bayar'])->name('pembayaran.bayar');
        Route::get('list-pembayaran-tagihan/{nisn}/{tagihansiswa_id}', [TagihanController::class, 'listPembayaranTagihan'])->name('list.pembayaran.tagihan');
        Route::get('tagihan/{id}/{idtagihansiswa}', [TagihanController::class, 'tagihan'])->name('pembayaran.tagihan');
        Route::post('bayar/{nisn}', [TagihanController::class, 'prosesBayar'])->name('pembayaran.proses-bayar');
        Route::get('status-pembayaran', [TagihanController::class, 'statusPembayaran'])
            ->name('pembayaran.status-pembayaran');

        Route::get('status-pembayaran/{siswa:nisn}', [TagihanController::class, 'statusPembayaranShow'])
            ->name('pembayaran.status-pembayaran.show');

        Route::get('status-pembayaran/{nisn}/{tahun}', [TagihanController::class, 'statusPembayaranShowStatus'])
            ->name('pembayaran.status-pembayaran.show-status');

        Route::get('history-pembayaran', [TagihanController::class, 'historyPembayaran'])
            ->name('pembayaran.history-pembayaran');

        Route::get('history-pembayaran/preview/{id}', [TagihanController::class, 'printHistoryPembayaran'])
            ->name('pembayaran.history-pembayaran.print');

        Route::get('laporan', [TagihanController::class, 'laporan'])->name('pembayaran.laporan');
        Route::post('laporan', [TagihanController::class, 'printPdf'])->name('pembayaran.print-pdf');
    });
});

Route::prefix('admin')
    ->namespace('Admin')
    ->middleware(['auth'])
    ->group(function () {
        Route::middleware(['role:admin'])->group(function () {
            Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
            Route::get('admin-list', 'AdminListController@index')->name('admin-list.index');
            Route::get('admin-list/create', 'AdminListController@create')->name('admin-list.create');
            Route::post('admin-list', 'AdminListController@store')->name('admin-list.store');
            Route::get('admin-list/{id}/edit', 'AdminListController@edit')->name('admin-list.edit');
            Route::patch('admin-list/{id}', 'AdminListController@update')->name('admin-list.update');
            Route::delete('admin-list/{id}', 'AdminListController@destroy')->name('admin-list.destroy');
            Route::resource('user', 'UserController');
            Route::resource('petugas', 'PetugasController');
            Route::resource('permissions', 'PermissionController');
            Route::resource('roles', 'RoleController');
            Route::get('role-permission', 'RolePermissionController@index')->name('role-permission.index');
            Route::get('role-permission/create/{id}', 'RolePermissionController@create')->name('role-permission.create');
            Route::post('role-permission/create/{id}', 'RolePermissionController@store')->name('role-permission.store');
            Route::get('user-role', 'UserRoleController@index')->name('user-role.index');
            Route::get('user-role/create/{id}', 'UserRoleController@create')->name('user-role.create');
            Route::post('user-role/create/{id}', 'UserRoleController@store')->name('user-role.store');
            Route::get('user-permission', 'UserPermissionController@index')->name('user-permission.index');
            Route::get('user-permission/create/{id}', 'UserPermissionController@create')->name('user-permission.create');
            Route::post('user-permission/create/{id}', 'UserPermissionController@store')->name('user-permission.store');
        });

        // Route::middleware(['role:petugas'])->group(function () {
        //     Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
        //     Route::get('admin-list', 'AdminListController@index')->name('admin-list.index');
        //     Route::get('admin-list/create', 'AdminListController@create')->name('admin-list.create');
        //     Route::post('admin-list', 'AdminListController@store')->name('admin-list.store');
        //     Route::get('admin-list/{id}/edit', 'AdminListController@edit')->name('admin-list.edit');
        //     Route::patch('admin-list/{id}', 'AdminListController@update')->name('admin-list.update');
        //     Route::delete('admin-list/{id}', 'AdminListController@destroy')->name('admin-list.destroy');
        //     Route::resource('user', 'UserController');
        //     Route::resource('petugas', 'PetugasController');
        // });

        Route::middleware(['role:petugas'])->group(function () {
            Route::resource('spp', 'SppController');
            // tambahan tagihan
            Route::resource('tagihan', 'TagihanController');
            Route::resource('pembayaran-spp', 'PembayaranController');
            Route::resource('kelas', 'KelasController');
            Route::resource('siswa', 'SiswaController');
            Route::delete('delete-all-siswa', 'CheckBoxDeleteController@deleteAllSiswa')
                ->name('delete-all-siswa');
        });
    });

Route::prefix('siswa')->middleware(['auth', 'role:siswa'])->group(function () {

    Route::prefix('permas')->group(function () {

        Route::prefix('pembayaran')->group(function () {
            Route::get('bayar/{nisn}', [PembayaranPermasController::class, 'bayar'])->name('siswa.pembayaran-permas.bayar');
            Route::post('bayar/{nisn}', [PembayaranPermasController::class, 'prosesBayar'])->name('siswa.pembayaran-permas.proses-bayar');
            Route::get('spp/{tahun}/{nisn}', [PembayaranPermasController::class, 'spp'])->name('siswa.pembayaran-permas.spp');
            Route::get('/', [PembayaranPermasController::class, 'pembayaran'])->name('siswa.pembayaran-permas.index');
            Route::get('{spp:tahun}', [PembayaranPermasController::class, 'pembayaranShow'])->name('siswa.pembayaran-permas.show');
        });

        Route::prefix('history')->group(function () {
            Route::get('/', 'PembayaranPermasController@history')->name('siswa.history-permas.index');
            Route::get('{id}', 'PembayaranPermasController@historyShow')->name('siswa.history-permas.show');
        });

        Route::prefix('laporan')->group(function () {
            Route::get('/', 'PembayaranPermasController@laporan')->name('siswa.laporan-permas.index');
            Route::post('print-laporan', 'PembayaranPermasController@laporanshow')->name('siswa.laporan-permas.show');
        });
    });

    Route::prefix('tagihan')->group(function () {

        Route::prefix('pembayaran')->group(function () {
            Route::get('bayar/{nisn}', [PembayaranTagihanController::class, 'bayar'])->name('siswa.pembayaran-tagihan.bayar');
            Route::post('bayar/{nisn}', [PembayaranTagihanController::class, 'prosesBayar'])->name('siswa.pembayaran-tagihan.proses-bayar');
            Route::get('tagihan/{id}/{idtagihansiswa}', [PembayaranTagihanController::class, 'tagihan'])->name('siswa.pembayaran-tagihan.tagihan');
            Route::get('list-pembayaran-tagihan/{nisn}/{tagihansiswa_id}', [PembayaranTagihanController::class, 'listPembayaranTagihan'])->name('list.pembayaran.tagihan');
            Route::get('/', [PembayaranTagihanController::class, 'pembayaran'])->name('siswa.pembayaran-tagihan.index');
            Route::get('{spp:tahun}', [PembayaranTagihanController::class, 'pembayaranShow'])->name('siswa.pembayaran-tagihan.show');
        });

        Route::prefix('history')->group(function () {
            Route::get('/', 'PembayaranTagihanController@history')->name('siswa.history-tagihan.index');
            Route::get('{id}', 'PembayaranTagihanController@historyShow')->name('siswa.history-tagihan.show');
        });

        Route::prefix('laporan')->group(function () {
            Route::get('/', 'PembayaranTagihanController@laporanPembayaran')->name('siswa.laporan-tagihan.index');
            Route::post('print-laporan', 'PembayaranTagihanController@laporanshow')->name('siswa.laporan-tagihan.show');
        });
    });

    Route::get('pembayaran-spp', 'SiswaController@pembayaranSpp')->name('siswa.pembayaran-spp');
    Route::get('pembayaran-spp/{spp:tahun}', 'SiswaController@pembayaranSppShow')->name('siswa.pembayaran-spp.pembayaranSppShow');
    Route::get('history-pembayaran', 'SiswaController@historyPembayaran')->name('siswa.history-pembayaran');
    Route::get('history-pembayaran/preview/{id}', 'SiswaController@previewHistoryPembayaran')->name('siswa.history-pembayaran.preview');
    Route::get('laporan-pembayaran', 'SiswaController@laporanPembayaran')->name('siswa.laporan-pembayaran');
    Route::post('laporan-pembayaran', 'SiswaController@printPdf')->name('siswa.laporan-pembayaran.print-pdf');
});

Route::prefix('profile')
    ->name('profile.')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', 'ProfileController@index')->name('index');
        Route::patch('/', 'ProfileController@update')->name('update');
    });
