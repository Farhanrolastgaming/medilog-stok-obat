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
        Schema::create('stok_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained()->onDelete('cascade');
            $table->foreignId('pemasok_id')->constrained()->onDelete('cascade');
            
            // KOLOM MEREK (Boleh dikosongkan / Nullable)
            $table->string('merek')->nullable(); 
            
            $table->integer('stok');
            $table->date('expired_date')->nullable();
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('stok_batches');
    }
};
