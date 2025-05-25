<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi')->unique();
            $table->string('no_po')->unique();
            $table->date('tanggal_po');
            $table->date('tanggal_kirim');
            $table->foreignId('barang_id')->constrained('data_barangs')->onDelete('cascade');
            $table->foreignId('pembeli_id')->constrained('pembelis')->onDelete('cascade');
            $table->decimal('harga_jual', 15, 2);
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};