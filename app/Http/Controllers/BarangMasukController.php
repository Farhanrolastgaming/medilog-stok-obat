<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Pemasok;
use App\Models\DetailTransaksi;
use App\Models\User;
use Illuminate\Http\Request;

class BarangMasukController
{
    public function index()
    {
        $transaksis = Transaksi::with('Pemasok', 'User', 'DetailTransaksi')
            ->whereNotNull('pemasok_id')
            ->get();
        return view('barang-masuk.index', compact('transaksis'));
    }

    public function create()
    {
        $obats = Obat::all();
        $pemasoks = Pemasok::all();
        return view('barang-masuk.create', compact('obats', 'pemasoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'required|exists:pemasoks,id',
            'tanggal_transaksi' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obats,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'integer|min:0'
        ]);

        $totalHarga = 0;
        foreach ($request->harga_beli as $key => $harga) {
            $totalHarga += $harga * $request->jumlah[$key];
        }

        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'pemasok_id' => $request->pemasok_id,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'total_harga' => $totalHarga
        ]);

        foreach ($request->obat_id as $key => $obatId) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'obat_id' => $obatId,
                'harga_beli' => $request->harga_beli[$key],
                'jumlah_masuk' => $request->jumlah[$key],
                'subtotal' => $request->harga_beli[$key] * $request->jumlah[$key],
                'masa_kadaluwarsa' => $request->masa_kadaluwarsa[$key] ?? null
            ]);

            $obat = Obat::find($obatId);
            $obat->stok = ($obat->stok ?? 0) + $request->jumlah[$key];
            $obat->save();
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        foreach ($transaksi->DetailTransaksi as $detail) {
            $obat = Obat::find($detail->obat_id);
            $obat->stok = ($obat->stok ?? 0) - $detail->jumlah_masuk;
            $obat->save();
        }

        $transaksi->delete();
        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dihapus');
    }
}
