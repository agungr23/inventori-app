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
        Schema::create('harga_pembelis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('data_barangs')->onDelete('cascade');
            $table->decimal('harga_jual', 15, 2);
            $table->foreignId('pembeli_id')->constrained('pembelis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_pembelis');
    }
};