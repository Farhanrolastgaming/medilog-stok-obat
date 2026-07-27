@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-file-invoice text-2xl"></i>
    <h1 class="text-2xl font-semibold">Laporan Stok</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 no-print">
    <form action="" method="GET" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tanggal</label>
            <select name="sort_order" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon text-sm bg-white">
                <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
            </select>
        </div>
        <div class="flex flex-wrap gap-2 ml-auto">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button type="submit" name="export_excel" value="1" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 flex items-center gap-2 transition-colors">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button type="submit" name="cetak" value="1" formtarget="_blank" class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 flex items-center gap-2 transition-colors">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <span class="text-sm text-gray-700 font-medium">Total Aktivitas Mutasi: {{ $mutasiStok->count() }} Transaksi</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 bg-gray-100 border-b-2 border-gray-200 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-3 border-r border-gray-200 font-bold text-center">Tanggal</th>
                        <th class="py-3 px-3 border-r border-gray-200 font-bold text-center">Tipe</th>
                        <th class="py-3 px-3 border-r border-gray-200 font-bold text-center">No. Transaksi</th>
                        <th class="py-3 px-3 border-r border-gray-200 font-bold text-center">Keterangan / Pemasok</th>
                        <th class="py-3 px-3 border-r border-gray-200 font-bold text-center">Obat & Merek</th>
                        <th class="py-3 px-3 border-r border-gray-200 font-bold text-center">Kuantitas</th>
                        <th class="py-3 px-3 border-r border-gray-200 font-bold text-center">Harga Satuan</th>
                        <th class="py-3 px-3 font-bold text-center">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mutasiStok as $mutasi)
                    @php
                        // Logika Penentuan Tipe Transaksi Berdasarkan Minus/Plus Jumlah
                        $isMasuk = $mutasi->jumlah_masuk > 0;
                        $tipeLabel = $isMasuk ? 'Masuk' : 'Keluar';
                        $tipeColor = $isMasuk ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                        $noTransaksi = $mutasi->transaksi->kode_transaksi;
                        
                        // Keterangan khusus
                        $keterangan = $isMasuk ? ($mutasi->transaksi->Pemasok->nama_pemasok ?? 'Pemasok Dihapus') : 'Kasir';
                        $jumlah = abs($mutasi->jumlah_masuk);
                        
                        // Menghitung harga satuan sesuai tipe (Harga Beli vs Harga Jual)
                        $hargaSatuan = $isMasuk ? $mutasi->harga_beli : ($jumlah > 0 ? $mutasi->subtotal / $jumlah : 0);
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-3 border-r border-gray-200 whitespace-nowrap text-gray-600">
                            {{ \Carbon\Carbon::parse($mutasi->transaksi->tanggal_transaksi)->format('d-m-Y') }}
                        </td>
                        <td class="py-4 px-3 border-r border-gray-200 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $tipeColor }}">{{ $tipeLabel }}</span>
                        </td>
                        <td class="py-4 px-3 border-r border-gray-200 font-medium text-gray-900">{{ $noTransaksi }}</td>
                        <td class="py-4 px-3 border-r border-gray-200 text-gray-700">{{ $keterangan }}</td>
                        <td class="py-4 px-3 border-r border-gray-200">
                            <span class="font-bold text-gray-900 block">{{ $mutasi->obat->nama_obat ?? 'Obat Dihapus' }}</span>
                            <span class="text-xs text-brandMaroon font-medium">{{ $mutasi->merek ?? 'Generik' }}</span> 
                            <span class="text-xs text-gray-500">({{ $mutasi->obat->jenis ?? '' }})</span>
                        </td>
                        <td class="py-4 px-3 border-r border-gray-200 text-center font-bold text-lg {{ $isMasuk ? 'text-green-600' : 'text-red-600' }}">
                            {{ $isMasuk ? '+' : '-' }}{{ $jumlah }} <span class="text-xs font-normal text-gray-500">{{ $mutasi->obat->satuan ?? '' }}</span>
                        </td>
                        <td class="py-4 px-3 border-r border-gray-200 text-right text-gray-600">
                            Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-3 text-right font-bold text-gray-900">
                            Rp {{ number_format($mutasi->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 px-4 text-center text-gray-500 bg-gray-50">
                            <i class="fas fa-clipboard-list text-5xl mb-4 text-gray-300"></i>
                            <p class="text-xl font-medium text-gray-600">Belum ada aktivitas mutasi stok</p>
                            <p class="text-sm mt-1">Transaksi barang masuk atau keluar akan otomatis terekam di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* CSS Print yang lebih rapi */
    @media print {
        .no-print { display: none !important; }
        body { background-color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .shadow-sm { box-shadow: none !important; }
        .border-gray-200 { border-color: #e5e7eb !important; }
    }
</style>
@endsection