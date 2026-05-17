<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\User;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class DashboardController
{
    public function index()
    {
        $totalObat = Obat::count();
        $totalJenisObat = Obat::distinct('jenis_obat')->count('jenis_obat');
        $totalPengguna = User::count();
        $totalObatMasuk = DetailTransaksi::sum('jumlah_masuk') ?? 0;

        $obatNeedAttention = Obat::where('stok', '<', 10)->limit(10)->get();

        return view('dasboard', [
            'totalObat' => $totalObat,
            'totalObatMasuk' => $totalObatMasuk,
            'totalObatKeluar' => 0,
            'totalJenisObat' => $totalJenisObat,
            'totalPengguna' => $totalPengguna,
            'obatNeedAttention' => $obatNeedAttention
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
