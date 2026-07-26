<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Pemasok;
use App\Models\DetailTransaksi;
use App\Models\StokBatch; // <-- JANGAN LUPA IMPORT INI
use App\Models\User;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        // Ubah bagian 'DetailTransaksi' menjadi 'DetailTransaksi.obat'
        $transaksis = Transaksi::with(['Pemasok', 'User', 'DetailTransaksi.obat'])
            ->whereNotNull('pemasok_id')
            ->get();
            
        return view('barang-masuk.index', compact('transaksis'));
    }

    public function create()
    {
        // 1. PROTEKSI ADMIN
        if (auth()->user() && !in_array(strtolower(auth()->user()->role), ['admin', 'owner'])) {
            return redirect()->route('barang-masuk.index')->with('error', 'Akses Ditolak: Hanya Admin / Owner yang diperkenankan mencatat Barang Masuk.');
        }

        $obats = Obat::all();
        $pemasoks = Pemasok::all();
        return view('barang-masuk.create', compact('obats', 'pemasoks'));
    }

    public function store(Request $request)
    {
        // 2. PROTEKSI ADMIN
        if (auth()->user() && !in_array(strtolower(auth()->user()->role), ['admin', 'owner'])) {
            return redirect()->route('barang-masuk.index')->with('error', 'Akses Ditolak: Hanya Admin / Owner yang diperkenankan mencatat Barang Masuk.');
        }

        $request->validate([
            'pemasok_id' => 'required|exists:pemasoks,id',
            'tanggal_transaksi' => 'required|date',
            'obat_id' => 'required|array',
            'obat_id.*' => 'exists:obats,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'integer|min:0',
            'harga_jual' => 'required|array',
            'harga_jual.*' => 'integer|min:0',
            'merek' => 'nullable|array', 
            'masa_kadaluwarsa' => 'nullable|array',
            'masa_kadaluwarsa.*' => 'nullable|date'
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
            $expiredDate = $request->masa_kadaluwarsa[$key] ?? null;

            // 1. Catat Detail Transaksi Masuk
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'obat_id' => $obatId,
                'merek' => $request->merek[$key] ?? null, // Jika form kosong, masuk ke DB sebagai null
                'harga_beli' => $request->harga_beli[$key],
                'jumlah_masuk' => $request->jumlah[$key],
                'subtotal' => $request->harga_beli[$key] * $request->jumlah[$key],
                'masa_kadaluwarsa' => $expiredDate
            ]);

            // 2. Update Stok & Harga Jual Master Obat
            $obat = Obat::find($obatId);
            $obat->stok = ($obat->stok ?? 0) + $request->jumlah[$key];
            $obat->harga_jual = $request->harga_jual[$key];
            $obat->save();

            // 3. Simpan ke Stok Batch beserta Mereknya
            StokBatch::create([
                'obat_id' => $obatId,
                'pemasok_id' => $request->pemasok_id,
                'merek' => $request->merek[$key] ?? null, // Disimpan ke tabel stok_batches
                'stok' => $request->jumlah[$key],
                'expired_date' => $expiredDate,
                'harga_beli' => $request->harga_beli[$key],
                'harga_jual' => $request->harga_jual[$key],
            ]);
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('barang-masuk.index')->with('success', 'Riwayat transaksi barang masuk berhasil dihapus.');
    }
    public function cetak($id)
    {
        // Ambil data transaksi beserta detail, user, dan PEMASOK
        $transaksi = Transaksi::with(['DetailTransaksi.obat', 'User', 'Pemasok'])
                    ->whereNotNull('pemasok_id') // Memastikan ini adalah Barang Masuk
                    ->findOrFail($id);

        return view('barang-masuk.cetak', compact('transaksi'));
    }
}