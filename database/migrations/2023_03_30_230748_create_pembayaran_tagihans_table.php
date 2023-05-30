<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTagihansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembayaran')->nullable();
            $table->foreignId('petugas_id')->constrained('petugas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('tagihansiswa_id')->constrained('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nisn')->nullable();
            $table->enum('status', ['finish', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_tagihans');
    }
}
