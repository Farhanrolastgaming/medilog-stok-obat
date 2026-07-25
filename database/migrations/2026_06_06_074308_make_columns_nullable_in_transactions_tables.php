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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('pemasok_id')->nullable()->change();
        });

        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->date('masa_kadaluwarsa')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('pemasok_id')->nullable(false)->change();
        });

        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->date('masa_kadaluwarsa')->nullable(false)->change();
        });
    }
};
