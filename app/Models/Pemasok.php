<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $fillable = ['nama_pemasok','info_kontak'];

    public function transaksis()
    {
        return $this ->hasMany(Transaksi::class);
    }
}
