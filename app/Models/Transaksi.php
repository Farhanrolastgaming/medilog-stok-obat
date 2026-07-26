<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

class Transaksi extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan ini
    protected $fillable = ['user_id', 'pemasok_id', 'tanggal_transaksi', 'total_harga'];


    public function Pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function DetailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($transaksi) {
            $transaksi->DetailTransaksi()->delete();
        });
    }

    public function getKodeTransaksiAttribute()
    {
        $prefix = is_null($this->pemasok_id) ? 'TK-' : 'TM-';
        $query = self::whereDate('tanggal_transaksi', $this->tanggal_transaksi)
                     ->where('id', '<=', $this->id);

        if (is_null($this->pemasok_id)) {
            $query->whereNull('pemasok_id'); // Khusus Barang Keluar
        } else {
            $query->whereNotNull('pemasok_id'); // Khusus Barang Masuk
        }

        $urutan = $query->count();
        return $prefix . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }



}
