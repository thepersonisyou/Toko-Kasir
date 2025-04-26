n<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Penjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->default('TRX-' . time()); // misalnya default value
            $table->string('nama_pelanggan_manual')->nullable();
            $table->string('no_telp_manual')->nullable();
            $table->date('tanggal_penjualan');
            $table->decimal('total_harga');
            $table->decimal('bayar');
            $table->foreignId('pelanggan_id')->nullable();
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
        Schema::dropIfExists('penjualan');
    }
}
