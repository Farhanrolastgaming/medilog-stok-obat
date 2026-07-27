@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-clipboard-check text-2xl"></i>
    <h1 class="text-2xl font-semibold">Laporan Barang Masuk</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 no-print">
    <form method="GET" action="{{ route('report.barang-masuk') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" class="px-4 h-[42px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon text-sm bg-white" value="{{ request('tanggal_dari') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" class="px-4 h-[42px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon text-sm bg-white" value="{{ request('tanggal_sampai') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Urutan Data</label>
            <select name="sort_order" class="px-4 h-[42px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon text-sm bg-white cursor-pointer">
                <option value="tanggal_desc" {{ request('sort_order', 'tanggal_desc') == 'tanggal_desc' ? 'selected' : '' }}>Tanggal Terbaru</option>
                <option value="tanggal_asc" {{ request('sort_order') == 'tanggal_asc' ? 'selected' : '' }}>Tanggal Terlama</option>
                <option value="pemasok_asc" {{ request('sort_order') == 'pemasok_asc' ? 'selected' : '' }}>Pemasok (A - Z)</option>
                <option value="pemasok_desc" {{ request('sort_order') == 'pemasok_desc' ? 'selected' : '' }}>Pemasok (Z - A)</option>
            </select>
        </div>
        <div class="flex flex-wrap items-center gap-2 ml-auto">
            <button type="submit" class="bg-blue-600 text-white px-4 h-[42px] rounded-lg hover:bg-blue-700 flex items-center gap-2 transition-colors font-medium cursor-pointer">
                <i class="fas fa-search"></i> Filter
            </button>
            <div class="relative inline-block text-left group">
                <button type="button" class="bg-green-600 text-white px-4 h-[42px] rounded-lg font-medium hover:bg-green-700 flex items-center gap-2 transition-colors cursor-pointer">
                    <i class="fas fa-print"></i> Cetak Laporan <i class="fas fa-chevron-down text-xs ml-1"></i>
                </button>
                <div class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 hidden group-hover:block hover:block z-50 py-1">
                    <button type="submit" name="cetak" value="1" formtarget="_blank" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2 transition font-medium cursor-pointer">
                        <i class="fas fa-file-pdf text-red-500 text-base"></i> Cetak PDF
                    </button>
                    <button type="submit" name="export_excel" value="1" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2 transition font-medium cursor-pointer">
                        <i class="fas fa-file-excel text-emerald-600 text-base"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <span class="text-sm text-gray-700 font-medium">Total Transaksi: {{ $transaksis->count() }}</span>
        <span class="text-sm font-bold text-brandMaroon">Total Nilai Pembelian: Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Tanggal</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Pemasok</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Obat & Merek</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">Jumlah</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-right">Harga Beli</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-right">Subtotal</th>
                        <th class="py-3 px-4 font-medium">Tgl Kedaluwarsa</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($transaksis as $transaksi)
                        @foreach ($transaksi->DetailTransaksi as $detail)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            
                            @if ($loop->first)
                            <td rowspan="{{ $transaksi->DetailTransaksi->count() }}" class="py-4 px-4 border-r border-gray-200 text-center align-top">{{ $no++ }}</td>
                            <td rowspan="{{ $transaksi->DetailTransaksi->count() }}" class="py-4 px-4 border-r border-gray-200 align-top whitespace-nowrap">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                            <td rowspan="{{ $transaksi->DetailTransaksi->count() }}" class="py-4 px-4 border-r border-gray-200 align-top">
                                <span class="font-medium text-gray-900">{{ $transaksi->Pemasok->nama_pemasok ?? '-' }}</span>
                            </td>
                            @endif
                            
                            <td class="py-4 px-4 border-r border-gray-200">
                                <span class="font-bold text-gray-900 block">{{ $detail->Obat->nama_obat ?? 'Dihapus' }}</span>
                                <span class="text-xs text-gray-500">{{ $detail->merek ?? 'Generik' }}</span>
                            </td>
                            <td class="py-4 px-4 border-r border-gray-200 text-center font-medium">{{ abs($detail->jumlah_masuk) }}</td>
                            <td class="py-4 px-4 border-r border-gray-200 text-right text-gray-600">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                            <td class="py-4 px-4 border-r border-gray-200 text-right font-medium text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td class="py-4 px-4 text-gray-600 whitespace-nowrap">
                                {{ $detail->masa_kadaluwarsa ? \Carbon\Carbon::parse($detail->masa_kadaluwarsa)->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Data laporan barang masuk tidak ada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print, form { display: none !important; }
        body { background-color: white; }
    }
</style>
@endsection