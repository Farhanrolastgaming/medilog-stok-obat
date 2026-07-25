<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemesananStok;
use App\Models\Pemasok; // Tambahkan ini untuk memanggil data Pemasok

class PemesananStokController extends Controller
{
    public function index()
    {
        if (auth()->user() && strtolower(auth()->user()->role) === 'admin') {
            // Admin melihat semua pesanan (jangan lupa muat relasi pemasok)
            $pemesanans = PemesananStok::with(['user', 'pemasok'])->latest()->get();
        } else {
            // User melihat pesanan miliknya sendiri
            $pemesanans = PemesananStok::with('pemasok')->where('user_id', auth()->id())->latest()->get();
        }

        return view('pemesanan.index', compact('pemesanans'));
    }

    public function create()
    {
        // Hanya User biasa yang boleh membuat pengajuan pemesanan baru
        if (auth()->user() && strtolower(auth()->user()->role) === 'admin') {
            return redirect()->route('pemesanan.index')->with('error', 'Admin tidak diperkenankan membuat pengajuan pemesanan.');
        }

        // Ambil data pemasok untuk ditampilkan di dropdown form
        $pemasoks = Pemasok::orderBy('nama_pemasok', 'asc')->get();
        
        return view('pemesanan.create', compact('pemasoks'));
    }

    public function store(Request $request)
    {
        if (auth()->user() && strtolower(auth()->user()->role) === 'admin') {
            return redirect()->route('pemesanan.index')->with('error', 'Admin tidak diperkenankan membuat pengajuan pemesanan.');
        }

        $request->validate([
            'nama_obat'  => 'required|string|max:255',
            'merek'      => 'nullable|string|max:255',
            'pemasok_id' => 'required|exists:pemasoks,id',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        PemesananStok::create([
            'user_id'    => auth()->id(),
            'pemasok_id' => $request->pemasok_id,
            'nama_obat'  => $request->nama_obat,
            'merek'      => $request->merek,
            'jumlah'     => $request->jumlah,
            'keterangan' => $request->keterangan,
            'status'     => 'Dalam Proses', // Status default baru
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Pengajuan pemesanan berhasil diajukan dan sedang Dalam Proses.');
    }

    // Fungsi baru pengganti approve: updateStatus
    public function updateStatus(Request $request, $id)
    {
        if (auth()->user() && strtolower(auth()->user()->role) !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:Dalam Proses,Ditolak,Diajukan,Selesai'
        ]);

        $pemesanan = PemesananStok::findOrFail($id);
        $pemesanan->update([
            'status' => $request->status,
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Status pengajuan berhasil diubah menjadi: ' . $request->status);
    }
    // Fungsi untuk mencetak Surat Pesanan (SP)
    public function cetak($id)
    {
        $pemesanan = PemesananStok::with(['user', 'pemasok'])->findOrFail($id);
        
        // Hanya izinkan cetak jika statusnya tidak Ditolak
        if (strtolower($pemesanan->status) === 'ditolak') {
            return back()->with('error', 'Surat Pesanan yang ditolak tidak dapat dicetak.');
        }

        return view('pemesanan.cetak', compact('pemesanan'));
    }
}