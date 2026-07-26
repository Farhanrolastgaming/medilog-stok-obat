<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasok;

class PemasokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data dari tabel pemasok
        $pemasoks = Pemasok::all();

        // Mengirim data tersebut ke file view bernama 'pemasok.index'
        return view('pemasok.index', compact('pemasoks'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pemasok.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'nama_pic'     => 'nullable|string|max:255',
            'telepon'      => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'alamat'       => 'nullable|string',
            'kota'         => 'nullable|string|max:100',
            'no_rekening'  => 'nullable|string|max:255',
            'info_kontak'   => 'nullable|string|max:255',
        ]);

        $data = $request->only(['nama_pemasok', 'nama_pic', 'telepon', 'email', 'alamat', 'kota', 'no_rekening', 'info_kontak']);
        if (empty($data['info_kontak'])) {
            $data['info_kontak'] = trim(($data['telepon'] ?? '') . ' ' . ($data['email'] ?? ''));
        }

        Pemasok::create($data);

        return redirect()->route('pemasok.index')->with('success', 'Data pemasok berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemasok = Pemasok::with('transaksis.detailTransaksis.obat')->findOrFail($id);
        return view('pemasok.show', compact('pemasok'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pemasok = Pemasok::findOrFail($id);
        return view('pemasok.edit', compact('pemasok'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'nama_pic'     => 'nullable|string|max:255',
            'telepon'      => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'alamat'       => 'nullable|string',
            'kota'         => 'nullable|string|max:100',
            'no_rekening'  => 'nullable|string|max:255',
            'info_kontak'   => 'nullable|string|max:255',
        ]);

        $pemasok = Pemasok::findOrFail($id);
        $data = $request->only(['nama_pemasok', 'nama_pic', 'telepon', 'email', 'alamat', 'kota', 'no_rekening', 'info_kontak']);
        if (empty($data['info_kontak'])) {
            $data['info_kontak'] = trim(($data['telepon'] ?? '') . ' ' . ($data['email'] ?? ''));
        }

        $pemasok->update($data);

        return redirect()->route('pemasok.index')->with('success', 'Data pemasok berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemasok = Pemasok::findOrFail($id);

        // Cek apakah pemasok ini memiliki riwayat transaksi atau pemesanan
        if ($pemasok->transaksis()->count() > 0) {
            return redirect()->route('pemasok.index')
                ->with('error', 'Gagal Hapus: Pemasok "' . $pemasok->nama_pemasok . '" tidak dapat dihapus karena masih memiliki riwayat transaksi barang masuk.');
        }

        try {
            $pemasok->delete();
            return redirect()->route('pemasok.index')->with('success', 'Data pemasok berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('pemasok.index')->with('error', 'Gagal Hapus: Pemasok ini masih digunakan oleh data transaksi/pemesanan lain.');
        }
    }
}
