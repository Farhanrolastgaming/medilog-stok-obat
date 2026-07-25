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
        Schema::create('obat_rusaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained()->onDelete('cascade');
            $table->foreignId('stok_batch_id')->nullable()->constrained('stok_batches')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->integer('jumlah');
            $table->string('alasan'); // e.g., 'Rusak saat Pengiriman Pemasok', 'Kemasan Pecah / Bocor', 'Rusak saat Pengiriman ke Pembeli', 'Lainnya'
            $table->text('keterangan')->nullable();
            $table->date('tanggal_lapor');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_rusaks');
    }
};
