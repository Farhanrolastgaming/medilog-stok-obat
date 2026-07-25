<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBatch extends Model
{
    use HasFactory;

    // Pastikan menggunakan guarded seperti ini agar semua kolom bebas diisi
    protected $guarded = ['id']; 

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}