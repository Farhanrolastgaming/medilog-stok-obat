<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = ['nama_obat','jenis_obat','satuan','harga_jual','stok'];
    
    public function DetailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
    
    
}
