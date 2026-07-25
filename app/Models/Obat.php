<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_obat', 
        'jenis_obat', 
        'golongan_obat',
        'komposisi',
        'aturan_pakai',
        'satuan', 
        'harga_beli', 
        'harga_jual', 
        'stok', 
        'kode_obat', 
        'expired_date'
    ];
    
    public function DetailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
    
    public function stokBatches()
    {
        return $this->hasMany(StokBatch::class);
    }

    public function obatRusaks()
    {
        return $this->hasMany(ObatRusak::class);
    }
}
