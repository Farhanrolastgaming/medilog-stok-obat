<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

class DetailTransaksi extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan ini

    // INI KUNCI UTAMANYA: Izinkan semua kolom untuk diisi (kecuali id)
    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}