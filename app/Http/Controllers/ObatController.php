<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController
{
    public function index()
    {
        $obats = Obat::all();
        return view('obat.index', compact('obats'));
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat'  => 'required|string|max:255',
            'jenis_obat' => 'required|string|max:255',
            'satuan'=> 'required|string|max:255',
            'harga_jual' => 'required|integer|min:0',
            'stok' => 'nullable|integer|min:0'
        ]);

        Obat::create($request->all());

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $obat = Obat::findOrFail($id);
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
            'nama_obat'  => 'required|string|max:255',
            'jenis_obat' => 'required|string|max:255',
            'satuan'=> 'required|string|max:255',
            'harga_jual' => 'required|integer|min:0',
            'stok' => 'nullable|integer|min:0'
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update($request->all());

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus');
    }
}
