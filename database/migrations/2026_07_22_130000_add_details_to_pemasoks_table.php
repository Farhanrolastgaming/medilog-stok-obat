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
        Schema::table('pemasoks', function (Blueprint $table) {
            $table->string('nama_pic')->nullable()->after('nama_pemasok');
            $table->string('telepon')->nullable()->after('nama_pic');
            $table->string('email')->nullable()->after('telepon');
            $table->text('alamat')->nullable()->after('email');
            $table->string('kota')->nullable()->after('alamat');
            $table->string('no_rekening')->nullable()->after('kota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasoks', function (Blueprint $table) {
            $table->dropColumn(['nama_pic', 'telepon', 'email', 'alamat', 'kota', 'no_rekening']);
        });
    }
};
