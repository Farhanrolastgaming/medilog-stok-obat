<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\StokBatch;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kode_obat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_obat')) {
            $query->where('jenis_obat', $request->jenis_obat);
        }

        if ($request->filled('golongan_obat')) {
            $query->where('golongan_obat', $request->golongan_obat);
        }

        $obats = $query->get();
        $jenisList = Obat::whereNotNull('jenis_obat')->distinct()->pluck('jenis_obat');
        $golonganList = Obat::whereNotNull('golongan_obat')->where('golongan_obat', '!=', '')->distinct()->pluck('golongan_obat');

        return view('obat.index', compact('obats', 'jenisList', 'golonganList'));
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi data master obat
        $request->validate([
            'kode_obat'     => 'required|string|max:50|unique:obats,kode_obat',
            'nama_obat'     => 'required|string|max:255',
            'jenis_obat'    => 'required|string|max:100',
            'golongan_obat' => 'nullable|string|max:100',
            'komposisi'     => 'nullable|string',
            'aturan_pakai'  => 'nullable|string',
            'satuan'        => 'required|string|max:50',
        ]);

        // 2. Simpan data dengan menyuntikkan nilai 0 untuk stok dan harga awal
        Obat::create([
            'kode_obat'     => $request->kode_obat,
            'nama_obat'     => $request->nama_obat,
            'jenis_obat'    => $request->jenis_obat,
            'golongan_obat' => $request->golongan_obat,
            'komposisi'     => $request->komposisi,
            'aturan_pakai'  => $request->aturan_pakai,
            'satuan'        => $request->satuan,
            'stok'          => 0, // Otomatis 0, nanti bertambah lewat Barang Masuk
            'harga_jual'    => 0, // Otomatis 0, nanti diatur per batch / saat transaksi
            'harga_beli'    => 0, // Otomatis 0, nanti diatur per batch / saat transaksi
        ]);

        return redirect()->route('obat.index')->with('success', 'Data master obat baru berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $obat = Obat::with('stokBatches.pemasok')->findOrFail($id);
        return view('obat.show', compact('obat'));
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('obat.edit', compact('obat'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_obat'     => 'required|string|max:50|unique:obats,kode_obat,' . $id,
            'nama_obat'     => 'required|string|max:255',
            'jenis_obat'    => 'required|string|max:100',
            'golongan_obat' => 'nullable|string|max:100',
            'komposisi'     => 'nullable|string',
            'aturan_pakai'  => 'nullable|string',
            'satuan'        => 'required|string|max:50',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update($request->only([
            'kode_obat', 'nama_obat', 'jenis_obat', 'golongan_obat', 'komposisi', 'aturan_pakai', 'satuan'
        ]));

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus');
    }

    public function destroyBatch(string $batchId)
    {
        $batch = StokBatch::findOrFail($batchId);
        $obatId = $batch->obat_id;

        $batch->delete();

        // Hitung ulang total stok obat berdasarkan sisa batch
        $obat = Obat::find($obatId);
        if ($obat) {
            $obat->stok = $obat->stokBatches()->sum('stok');
            $obat->save();
        }

        return redirect()->route('obat.show', $obatId)->with('success', 'Riwayat batch stok berhasil dihapus');
    }
}