<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasok;
class PemasokController
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
            // 1. Validasi data yang dikirim dari form
            'nama_pemasok' => 'required|string|max:255',
            'info_kontak' => 'required|string|max:255',


        ]);
        Pemasok::create([
            'nama_pemasok' => $request->nama_pemasok,
            'info_kontak' => $request->info_kontak

        ]);
        // 3. Kembalikan user ke halaman index dengan pesan sukses
        return redirect()->route('pemasok.index')->with('success', 'Data pemasok berhasil ditambahkan');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            'info_kontak' => 'required|string|max:255',
        ]);

        $pemasok = Pemasok::findOrFail($id);
        $pemasok->update([
            'nama_pemasok' => $request->nama_pemasok,
            'info_kontak' => $request->info_kontak,
        ]);

        return redirect()->route('pemasok.index')->with('success', 'Data pemasok berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemasok = Pemasok::findOrFail($id);
        $pemasok->delete();

        return redirect()->route('pemasok.index')->with('success', 'Data pemasok berhasil dihapus');
    }
}
