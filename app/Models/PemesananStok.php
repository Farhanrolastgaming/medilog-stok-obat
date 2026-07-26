<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Pastikan model User dan Pemasok di-import (opsional jika dalam folder yang sama, tapi lebih aman ditulis)
use App\Models\User;
use App\Models\Pemasok;

class PemesananStok extends Model
{
    use HasFactory;

    // Pastikan semua kolom baru sudah masuk fillable
    protected $fillable = [
        'user_id', 
        'pemasok_id', 
        'obat_id',
        'nama_obat', 
        'merek',      
        'jumlah', 
        'keterangan', 
        'status'
    ];

    /**
     * Relasi ke tabel users (Mengetahui siapa yang mengajukan pesanan)
     * INI ADALAH FUNGSI YANG HILANG DAN MENYEBABKAN ERROR TADI
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel pemasoks (Mengetahui pemasok yang dituju)
     */
    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }
}