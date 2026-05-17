<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class BarangKeluarController
{
    public function index()
    {
        $transaksis = Transaksi::with('User', 'DetailTransaksi')
            ->whereNull('pemasok_id')
            ->get();
        return view('barang-keluar.index', compact('transaksis'));
    }

    public function create()
    {
        $obats = Obat::all();
        return view('barang-keluar.create', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obats,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1'
        ]);

        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'pemasok_id' => null,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'total_harga' => 0
        ]);

        foreach ($request->obat_id as $key => $obatId) {
            $jumlah = $request->jumlah[$key];

            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'obat_id' => $obatId,
                'harga_beli' => 0,
                'jumlah_masuk' => -$jumlah,
                'subtotal' => 0,
                'masa_kadaluwarsa' => null
            ]);

            $obat = Obat::find($obatId);
            $obat->stok = max(0, ($obat->stok ?? 0) - $jumlah);
            $obat->save();
        }

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        foreach ($transaksi->DetailTransaksi as $detail) {
            $obat = Obat::find($detail->obat_id);
            $obat->stok = ($obat->stok ?? 0) + abs($detail->jumlah_masuk);
            $obat->save();
        }

        $transaksi->delete();
        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil dihapus');
    }
}
