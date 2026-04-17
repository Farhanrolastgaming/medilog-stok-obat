<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $fillable = 
    [   'transaksi_id', 
        'obat_id', 
        'harga_beli', 
        'jumlah_masuk', 
        'subtotal', 
        'masa_kadaluwarsa'];

    public function Transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
    public function Obat()
    {
        return $this->belongsTo(Obat::class);
    }

    
}
