<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class ReportController
{
    public function laporanStok()
    {
        $obats = Obat::all();
        return view('report.stok', compact('obats'));
    }

    public function laporanBarangMasuk(Request $request)
    {
        $query = Transaksi::with('Pemasok', 'DetailTransaksi')->whereNotNull('pemasok_id');

        if ($request->tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        $transaksis = $query->get();
        return view('report.barang-masuk', compact('transaksis'));
    }

    public function laporanBarangKeluar(Request $request)
    {
        $query = Transaksi::with('DetailTransaksi')->whereNull('pemasok_id');

        if ($request->tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        $transaksis = $query->get();
        return view('report.barang-keluar', compact('transaksis'));
    }
}
