<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\User;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use App\Models\StokBatch;
use Carbon\Carbon; // Tambahkan ini untuk manipulasi tanggal yang lebih canggih

class DashboardController extends Controller
{
    public function index()
    {
        // ==========================================
        // 1. DATA STATISTIK UMUM (Kode Aslimu)
        // ==========================================
        $totalObat = Obat::count();
        $totalMerek = StokBatch::whereNotNull('merek')->where('merek', '!=', '')->distinct('merek')->count('merek');
        $totalJenisObat = Obat::distinct('jenis_obat')->count('jenis_obat');
        $totalPengguna = User::count();

        $totalObatMasuk = DetailTransaksi::whereRaw('jumlah_masuk > 0')->sum('jumlah_masuk') ?? 0;
        $totalObatKeluar = abs(DetailTransaksi::whereRaw('jumlah_masuk < 0')->sum('jumlah_masuk') ?? 0);

        $today = now()->toDateString();
        $thirtyDaysFromNow = now()->addDays(30)->toDateString();

        // Obat yang sudah kedaluwarsa
        $obatExpired = StokBatch::with('obat')
                        ->whereNotNull('expired_date')
                        ->where('expired_date', '<=', $today)
                        ->where('stok', '>', 0)
                        ->get();

        // Obat yang mendekati kedaluwarsa (dalam 30 hari)
        $obatAlmostExpired = StokBatch::with('obat')
            ->whereNotNull('expired_date')
            ->whereBetween('expired_date', [now()->addDay()->toDateString(), $thirtyDaysFromNow])
            ->where('stok', '>', 0)
            ->get();

        // Obat dengan stok menipis (< 10)
        $obatNeedAttention = Obat::where('stok', '<', 10)->limit(10)->get();


        // ==========================================
        // 2. DATA FINANSIAL (Laba & Pendapatan)
        // ==========================================
        $hariIni = Carbon::today();
        $awalBulan = Carbon::now()->startOfMonth();

        // A. Finansial Hari Ini (Cari transaksi keluar hari ini)
        $detailHariIni = DetailTransaksi::whereHas('transaksi', function ($q) use ($hariIni) {
            $q->whereDate('tanggal_transaksi', $hariIni)->whereNull('pemasok_id');
        })->get();

        $pendapatanHariIni = $detailHariIni->sum('subtotal');
        $modalHariIni = $detailHariIni->sum(function($detail) {
            return abs($detail->jumlah_masuk) * $detail->harga_beli;
        });
        $labaHariIni = $pendapatanHariIni - $modalHariIni;

        // B. Finansial Bulan Ini (Cari transaksi keluar dari tgl 1 sampai hari ini)
        $detailBulanIni = DetailTransaksi::whereHas('transaksi', function ($q) use ($awalBulan) {
            $q->where('tanggal_transaksi', '>=', $awalBulan)->whereNull('pemasok_id');
        })->get();

        $pendapatanBulanIni = $detailBulanIni->sum('subtotal');
        $modalBulanIni = $detailBulanIni->sum(function($detail) {
            return abs($detail->jumlah_masuk) * $detail->harga_beli;
        });
        $labaBulanIni = $pendapatanBulanIni - $modalBulanIni;


        // 4. EWS TERPADU DENGAN GRANULARITAS PER-MEREK / PER-BATCH STOK
        $ewsItems = collect();
        $processedBatchIds = [];

        // 1. Sudah Kadaluarsa (Melewati Kadaluarsa per Merek)
        $batchExpired = StokBatch::with('obat')
            ->whereNotNull('expired_date')
            ->where('expired_date', '<=', $today)
            ->where('stok', '>', 0)
            ->get();

        foreach ($batchExpired as $b) {
            $processedBatchIds[] = $b->id;
            $merekName = $b->merek ?: 'Generik';
            $ewsItems->push([
                'type' => 'expired',
                'label' => 'Sudah Kadaluarsa',
                'badge' => 'bg-red-100 text-red-800 border-red-200',
                'icon' => 'fas fa-times-circle text-red-500',
                'obat_id' => $b->obat_id,
                'kode_obat' => $b->obat->kode_obat ?? '-',
                'nama_obat' => $b->obat->nama_obat ?? 'Obat Tidak Ditemukan',
                'merek' => $merekName,
                'jenis_obat' => $b->obat->jenis_obat ?? '-',
                'stok' => $b->stok,
                'tgl_exp' => $b->expired_date ? Carbon::parse($b->expired_date)->format('d-m-Y') : '-',
                'keterangan' => 'Obat telah melewati tgl kedaluwarsa (' . ($b->expired_date ? Carbon::parse($b->expired_date)->format('d-m-Y') : '-') . ')'
            ]);
        }

        // 2. Akan Kadaluarsa (< 1 bulan per Merek)
        $batchAlmostExpired = StokBatch::with('obat')
            ->whereNotNull('expired_date')
            ->whereBetween('expired_date', [now()->addDay()->toDateString(), $thirtyDaysFromNow])
            ->where('stok', '>', 0)
            ->whereNotIn('id', $processedBatchIds)
            ->get();

        foreach ($batchAlmostExpired as $b) {
            $processedBatchIds[] = $b->id;
            $merekName = $b->merek ?: 'Generik';
            $ewsItems->push([
                'type' => 'almost_expired',
                'label' => 'Akan Kadaluarsa',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-200',
                'icon' => 'fas fa-exclamation-triangle text-amber-500',
                'obat_id' => $b->obat_id,
                'kode_obat' => $b->obat->kode_obat ?? '-',
                'nama_obat' => $b->obat->nama_obat ?? 'Obat Tidak Ditemukan',
                'merek' => $merekName,
                'jenis_obat' => $b->obat->jenis_obat ?? '-',
                'stok' => $b->stok,
                'tgl_exp' => $b->expired_date ? Carbon::parse($b->expired_date)->format('d-m-Y') : '-',
                'keterangan' => 'Merek ' . $merekName . ' akan kedaluwarsa kurang dari 1 bulan (' . ($b->expired_date ? Carbon::parse($b->expired_date)->format('d-m-Y') : '-') . ')'
            ]);
        }

        // 3. Stok Habis (Apabila stok 0 per Merek / Batch atau Master Obat)
        $batchHabis = StokBatch::with('obat')->where('stok', 0)->whereNotIn('id', $processedBatchIds)->get();
        foreach ($batchHabis as $b) {
            $processedBatchIds[] = $b->id;
            $merekName = $b->merek ?: 'Generik';
            $ewsItems->push([
                'type' => 'out_of_stock',
                'label' => 'Stok Habis',
                'badge' => 'bg-rose-100 text-rose-800 border-rose-200',
                'icon' => 'fas fa-ban text-rose-600',
                'obat_id' => $b->obat_id,
                'kode_obat' => $b->obat->kode_obat ?? '-',
                'nama_obat' => $b->obat->nama_obat ?? 'Obat Tidak Ditemukan',
                'merek' => $merekName,
                'jenis_obat' => $b->obat->jenis_obat ?? '-',
                'stok' => 0,
                'tgl_exp' => '-',
                'keterangan' => 'Stok habis, harap pesan lagi '
            ]);
        }

        // Master obat dengan stok 0 yang tidak memiliki batch
        $obatTanpaBatchHabis = Obat::where('stok', 0)->whereDoesntHave('stokBatches')->get();
        foreach ($obatTanpaBatchHabis as $o) {
            $ewsItems->push([
                'type' => 'out_of_stock',
                'label' => 'Stok Habis',
                'badge' => 'bg-rose-100 text-rose-800 border-rose-200',
                'icon' => 'fas fa-ban text-rose-600',
                'obat_id' => $o->id,
                'kode_obat' => $o->kode_obat ?? '-',
                'nama_obat' => $o->nama_obat,
                'merek' => '-',
                'jenis_obat' => $o->jenis_obat ?? '-',
                'stok' => 0,
                'tgl_exp' => '-',
                'keterangan' => 'Stok obat habis, harap pesan ulang'
            ]);
        }

        // 4. Stok Menipis (Apabila stok per Merek/Batch dibawah 10)
        $batchStokMenipis = StokBatch::with('obat')
            ->where('stok', '>', 0)
            ->where('stok', '<', 10)
            ->whereNotIn('id', $processedBatchIds)
            ->get();

        foreach ($batchStokMenipis as $b) {
            $merekName = $b->merek ?: 'Generik';
            $ewsItems->push([
                'type' => 'low_stock',
                'label' => 'Stok Menipis',
                'badge' => 'bg-orange-100 text-orange-800 border-orange-200',
                'icon' => 'fas fa-box-open text-orange-500',
                'obat_id' => $b->obat_id,
                'kode_obat' => $b->obat->kode_obat ?? '-',
                'nama_obat' => $b->obat->nama_obat ?? 'Obat Tidak Ditemukan',
                'merek' => $merekName,
                'jenis_obat' => $b->obat->jenis_obat ?? '-',
                'stok' => $b->stok,
                'tgl_exp' => '-',
                'keterangan' => 'Stok obat tersisa ' . $b->stok . ' pcs, segera pesan ulang!'
            ]);
        }


        // Return ke view
        return view('dasboard', [
            'totalObat' => $totalObat,
            'totalMerek' => $totalMerek,
            'totalObatMasuk' => $totalObatMasuk,
            'totalObatKeluar' => $totalObatKeluar,
            'totalJenisObat' => $totalJenisObat,
            'totalPengguna' => $totalPengguna,
            'obatNeedAttention' => $obatNeedAttention,
            'obatExpired' => $obatExpired,
            'obatAlmostExpired' => $obatAlmostExpired,
            'ewsItems' => $ewsItems,
            // Variabel Finansial Baru:
            'pendapatanHariIni' => $pendapatanHariIni,
            'labaHariIni' => $labaHariIni,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'labaBulanIni' => $labaBulanIni,
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
