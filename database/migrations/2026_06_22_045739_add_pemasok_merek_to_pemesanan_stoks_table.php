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
        Schema::table('pemesanan_stoks', function (Blueprint $table) {
            // Menambahkan kolom pemasok_id dan merek
            $table->unsignedBigInteger('pemasok_id')->nullable()->after('user_id');
            $table->string('merek')->nullable()->after('nama_obat');

            // Jika tabel pemasoks sudah ada, kita jadikan foreign key
            $table->foreign('pemasok_id')->references('id')->on('pemasoks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_stoks', function (Blueprint $table) {
            // Menghapus relasi dan kolom jika di-rollback
            $table->dropForeign(['pemasok_id']);
            $table->dropColumn(['pemasok_id', 'merek']);
        });
    }
};