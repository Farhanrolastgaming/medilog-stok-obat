<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable =['user_id', 'pemasok_id', 'tanggal_transaksi', 'total_harga'];


    public function Pemasok()
    {
        return $this->belongsTo(Pemasok::class);
        }
    public function User()
    {
        return $this->belongsTo(User::class);
        }
    public function DetailTransaksi()
    {
        return $this->belongsTo(DetailTransaksi::class);
        }
   


    

}
