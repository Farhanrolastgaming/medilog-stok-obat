<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\DetailTransaksi;
use App\Models\StokBatch; // <-- JANGAN LUPA TAMBAHKAN INI
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('User', 'DetailTransaksi')
            ->whereNull('pemasok_id')
            ->get();
        return view('barang-keluar.index', compact('transaksis'));
    }

    public function create()
    {
        // KITA UBAH: Ambil data dari StokBatch (bukan Obat::all)
        // Gabungkan stok berdasarkan Obat dan Merek agar siap tampil di Dropdown
        $stokTersedia = StokBatch::with('obat')
            ->where('stok', '>', 0)
            ->selectRaw('obat_id, merek, MAX(harga_jual) as harga_jual, SUM(stok) as total_stok')
            ->groupBy('obat_id', 'merek')
            ->get();

        return view('barang-keluar.create', compact('stokTersedia'));
    }

    public function store(Request $request)
    {
        // Validasi input array gabungan Obat & Merek
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'obat_merek' => 'required|array', // Array gabungan ID Obat dan Merek
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1'
        ]);

        $totalHargaTransaksi = 0;

        // 1. VALIDASI STOK TOTAL DULU (Cegah transaksi terpotong setengah jika gagal)
        foreach ($request->obat_merek as $key => $obatMerek) {
            // Pecah gabungan value "obat_id|merek" dari form
            list($obatId, $merek) = explode('|', $obatMerek);
            $merek = $merek === 'Generik' ? null : $merek;
            $jumlahDiminta = $request->jumlah[$key];

            $totalStokTersedia = StokBatch::where('obat_id', $obatId)
                ->where('merek', $merek)
                ->sum('stok');

            if ($totalStokTersedia < $jumlahDiminta) {
                $namaObat = Obat::find($obatId)->nama_obat;
                $namaMerek = $merek ?? 'Generik';
                return back()
                    ->withErrors("Stok {$namaObat} ({$namaMerek}) tidak cukup. Tersedia: {$totalStokTersedia}, Diminta: {$jumlahDiminta}")
                    ->withInput();
            }
        }

        // 2. BUAT TRANSAKSI INDUK
        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'pemasok_id' => null,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'total_harga' => 0 // Akan kita perbarui setelah FEFO selesai
        ]);

        // 3. PROSES FEFO (First Expired First Out) & PEMOTONGAN STOK
        foreach ($request->obat_merek as $key => $obatMerek) {
            list($obatId, $merek) = explode('|', $obatMerek);
            $merek = $merek === 'Generik' ? null : $merek;
            $jumlahDiminta = $request->jumlah[$key];

            // Ambil semua batch obat ini, urutkan dari Kadaluwarsa terdekat (ASC)
            $batches = StokBatch::where('obat_id', $obatId)
                ->where('merek', $merek)
                ->where('stok', '>', 0)
                ->orderBy('expired_date', 'asc')
                ->get();

            $sisaDiminta = $jumlahDiminta;

            foreach ($batches as $batch) {
                if ($sisaDiminta == 0) break; // Jika permintaan sudah terpenuhi, hentikan loop

                // Tentukan berapa banyak yang bisa diambil dari batch ini
                $jumlahDiambil = min($batch->stok, $sisaDiminta);
                $subtotal = $jumlahDiambil * $batch->harga_jual;
                $totalHargaTransaksi += $subtotal;

                // Catat Detail Transaksi persis sesuai Batch yang dipotong
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'obat_id' => $obatId,
                    'merek' => $batch->merek,
                    'harga_beli' => $batch->harga_beli, // Biarkan harga_beli asli untuk hitung Laba nanti
                    'jumlah_masuk' => -$jumlahDiambil,  // Minus karena keluar
                    'subtotal' => $subtotal, // Subtotal dihitung dari HARGA JUAL
                    'masa_kadaluwarsa' => $batch->expired_date
                ]);

                // Kurangi stok pada Batch ini
                $batch->stok -= $jumlahDiambil;
                $batch->save();

                $sisaDiminta -= $jumlahDiambil;
            }

            // Terakhir, kurangi juga stok total pada Master Obat
            $obat = Obat::find($obatId);
            $obat->stok -= $jumlahDiminta;
            $obat->save();
        }

        // 4. UPDATE TOTAL HARGA TRANSAKSI
        $transaksi->update(['total_harga' => $totalHargaTransaksi]);

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil diproses oleh sistem');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('barang-keluar.index')->with('success', 'Riwayat transaksi barang keluar berhasil dihapus.');
    }

    public function cetak($id)
    {
        // Ambil data transaksi beserta detail dan usernya
        $transaksi = Transaksi::with(['DetailTransaksi.obat', 'User'])
                    ->whereNull('pemasok_id')
                    ->findOrFail($id);

        return view('barang-keluar.cetak', compact('transaksi'));
    }
}