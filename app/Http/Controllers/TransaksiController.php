<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Pemasok;
use App\Models\DetailTransaksi;
use App\Models\User;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('Pemasok', 'User', 'DetailTransaksi')->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $obats = Obat::all();
        $pemasoks = Pemasok::all();
        $users = User::all();
        return view('transaksi.create', compact('obats', 'pemasoks', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_transaksi' => 'required|date',
            'total_harga' => 'required|integer|min:0',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obats,id',
            'jumlah_masuk' => 'required|array',
            'jumlah_masuk.*' => 'integer|min:1'
        ]);

        $transaksi = Transaksi::create([
            'user_id' => $request->user_id,
            'pemasok_id' => $request->pemasok_id ?? null,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'total_harga' => $request->total_harga
        ]);

        foreach ($request->obat_id as $key => $obatId) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'obat_id' => $obatId,
                'harga_beli' => $request->harga_beli[$key] ?? 0,
                'jumlah_masuk' => $request->jumlah_masuk[$key],
                'subtotal' => ($request->harga_beli[$key] ?? 0) * $request->jumlah_masuk[$key],
                'masa_kadaluwarsa' => $request->masa_kadaluwarsa[$key] ?? null
            ]);

            $obat = Obat::find($obatId);
            $obat->stok = ($obat->stok ?? 0) + $request->jumlah_masuk[$key];
            $obat->save();
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('Pemasok', 'User', 'DetailTransaksi')->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $obats = Obat::all();
        $pemasoks = Pemasok::all();
        $users = User::all();
        return view('transaksi.edit', compact('transaksi', 'obats', 'pemasoks', 'users'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_transaksi' => 'required|date',
            'total_harga' => 'required|integer|min:0'
        ]);

        $transaksi->update($request->only('user_id', 'pemasok_id', 'tanggal_transaksi', 'total_harga'));

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }
}
