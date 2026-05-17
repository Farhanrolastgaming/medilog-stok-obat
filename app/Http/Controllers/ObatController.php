<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $obats = Obat::all();

        return view('obat.index',compact('obats'));
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
            'nama_obat'  => 'required|string|max:255',
            'jenis_obat' => 'required|string|max:255',
            'satuan'=> 'required|string|max:255'
        ]);

        Obat::create($request->all());

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
