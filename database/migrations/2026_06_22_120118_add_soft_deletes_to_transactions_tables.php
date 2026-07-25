<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->softDeletes(); // Menambahkan kolom deleted_at
        });

        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->softDeletes(); // Menambahkan kolom deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};