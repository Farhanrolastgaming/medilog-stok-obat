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

        return view('report.stok', compact('mutasiStok'));
    }

    public function laporanBarangMasuk(Request $request)
    {
        // Tambahkan withTrashed() pada induk Transaksi dan relasi DetailTransaksi
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

        return view('report.barang-masuk', compact('transaksis'));
    }

    public function laporanBarangKeluar(Request $request)
    {
        // Tambahkan withTrashed() pada induk Transaksi dan relasi DetailTransaksi
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