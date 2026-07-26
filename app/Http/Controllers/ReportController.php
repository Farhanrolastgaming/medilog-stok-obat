<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function laporanStok(Request $request)
    {
        // Ambil parameter sort, beri nilai default jika tidak ada
        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');

        // Gunakan join agar bisa mengakses kolom milik tabel transaksis
        $query = DetailTransaksi::withTrashed()
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->select('detail_transaksis.*') // PRO-TIP: Wajib agar ID detail tidak tertimpa ID transaksi utama
            ->with(['transaksi' => function($q) {
                $q->withTrashed();
            }, 'transaksi.Pemasok', 'obat']);

        // Filter tanggal (tetap mempertahankan logika lamamu)
        if ($request->tanggal_dari || $request->tanggal_sampai) {
            $query->whereHas('transaksi', function($q) use ($request) {
                $q->withTrashed();
                if ($request->tanggal_dari) {
                    $q->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
                }
                if ($request->tanggal_sampai) {
                    $q->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
                }
            });
        }

        // LOGIKA PENGURUTAN DATA
        if ($sortBy === 'kode_transaksi') {
            // Ganti 'kode_transaksi' di bawah sesuai nama kolom kode/no transaksimu (misal: no_transaksi)
            $query->orderBy('transaksis.kode_transaksi', $sortOrder);
        } else {
            // Default: Urut berdasarkan tanggal transaksi
            $query->orderBy('transaksis.tanggal_transaksi', $sortOrder);
        }

        $mutasiStok = $query->get();

        if ($request->has('cetak')) {
            return view('report.cetak-stok', compact('mutasiStok'));
        }

        if ($request->has('export_excel')) {
            $filename = "laporan-stok-" . date('Y-m-d') . ".csv";
            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use ($mutasiStok) {
                $file = fopen('php://output', 'w');
                // UTF-8 BOM for Excel
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, ['No', 'Tanggal Transaksi', 'Kode Transaksi', 'Kode Obat', 'Nama Obat', 'Merek', 'Jenis Obat', 'Pemasok', 'Jumlah Mutasi', 'Harga Beli (Rp)', 'Subtotal (Rp)']);

                foreach ($mutasiStok as $index => $item) {
                    $tanggal = \Carbon\Carbon::parse($item->transaksi->tanggal_transaksi ?? now())->format('d-m-Y');
                    $kodeTx = $item->transaksi->kode_transaksi ?? '-';
                    $kodeObat = $item->obat->kode_obat ?? '-';
                    $namaObat = $item->obat->nama_obat ?? 'Obat Dihapus';
                    $merek = $item->merek ?? 'Generik';
                    $jenis = $item->obat->jenis_obat ?? '-';
                    $pemasok = $item->transaksi->Pemasok->nama_pemasok ?? '-';
                    $jumlah = $item->jumlah_masuk;
                    $hargaBeli = $item->harga_beli;
                    $subtotal = $item->subtotal;

                    fputcsv($file, [$index + 1, $tanggal, $kodeTx, $kodeObat, $namaObat, $merek, $jenis, $pemasok, $jumlah, $hargaBeli, $subtotal]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('report.stok', compact('mutasiStok'));
    }

    public function laporanBarangMasuk(Request $request)
    {
        $query = Transaksi::withTrashed()
            ->with(['Pemasok', 'DetailTransaksi' => function($q) {
                $q->withTrashed();
            }, 'DetailTransaksi.obat'])
            ->whereNotNull('pemasok_id');

        if ($request->tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        $transaksis = $query->latest('tanggal_transaksi')->get();

        if ($request->has('cetak')) {
            return view('report.cetak-barang-masuk', compact('transaksis'));
        }

        if ($request->has('export_excel')) {
            $filename = "laporan-barang-masuk-" . date('Y-m-d') . ".csv";
            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use ($transaksis) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, ['No', 'No Transaksi', 'Tanggal Transaksi', 'Nama Pemasok', 'Total Item', 'Total Harga (Rp)']);

                foreach ($transaksis as $index => $t) {
                    $tanggal = \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d-m-Y');
                    $pemasok = $t->Pemasok->nama_pemasok ?? '-';
                    $totalItem = $t->DetailTransaksi->count();
                    $totalHarga = $t->total_harga;

                    fputcsv($file, [$index + 1, $t->kode_transaksi, $tanggal, $pemasok, $totalItem, $totalHarga]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('report.barang-masuk', compact('transaksis'));
    }

    public function laporanBarangKeluar(Request $request)
    {
        $query = Transaksi::withTrashed()
            ->with(['DetailTransaksi' => function($q) {
                $q->withTrashed();
            }, 'DetailTransaksi.obat'])
            ->whereNull('pemasok_id');

        if ($request->tanggal_dari) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_dari);
        }
        if ($request->tanggal_sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_sampai);
        }

        $transaksis = $query->latest('tanggal_transaksi')->get();

        if ($request->has('cetak')) {
            return view('report.cetak-barang-keluar', compact('transaksis'));
        }

        if ($request->has('export_excel')) {
            $filename = "laporan-barang-keluar-" . date('Y-m-d') . ".csv";
            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use ($transaksis) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, ['No', 'No Transaksi', 'Tanggal Transaksi', 'Total Item', 'Total Harga (Rp)']);

                foreach ($transaksis as $index => $t) {
                    $tanggal = \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d-m-Y');
                    $totalItem = $t->DetailTransaksi->count();
                    $totalHarga = $t->total_harga;

                    fputcsv($file, [$index + 1, $t->kode_transaksi, $tanggal, $totalItem, $totalHarga]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('report.barang-keluar', compact('transaksis'));
    }
    
    public function hapusPermanenDetail($id)
    {
        // Cari data yang berstatus soft-deleted sekalipun, lalu hapus total dari pangkalan data
        $detail = DetailTransaksi::withTrashed()->findOrFail($id);
        $detail->forceDelete(); 

        return back()->with('success', 'Data berhasil dihapus permanen dari sistem laporan.');
    }
}