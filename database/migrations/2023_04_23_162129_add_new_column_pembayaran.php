<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnPembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('order_id')->after('petugas_id')->unique()->nullable();
            $table->after('jumlah_bayar', function ($table) {
                $table->enum('type', ['Online', 'Offline'])->nullable();
                $table->enum('jenis', ['Parmas', 'SPP'])->nullable();
                $table->enum('status', ['Pending', 'Failed', 'Success'])->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            //
        });
    }
}
