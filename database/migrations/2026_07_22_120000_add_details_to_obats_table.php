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
        Schema::table('obats', function (Blueprint $table) {
            $table->string('golongan_obat')->nullable()->after('jenis_obat');
            $table->text('komposisi')->nullable()->after('golongan_obat');
            $table->text('aturan_pakai')->nullable()->after('komposisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obats', function (Blueprint $table) {
            $table->dropColumn(['golongan_obat', 'komposisi', 'aturan_pakai']);
        });
    }
};
