@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-clipboard-list text-2xl"></i>
    <h1 class="text-2xl font-semibold">Laporan Barang Keluar</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 no-print">
    <form method="GET" action="{{ route('report.barang-keluar') }}" class="grid grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('tanggal_dari') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="{{ request('tanggal_sampai') }}">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <i class="fas fa-search"></i> Filter
            </button>
            <button type="submit" name="cetak" value="1" formtarget="_blank" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2 transition-colors">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <span class="text-sm text-gray-700 font-medium">Total Transaksi: {{ $transaksis->count() }}</span>
        <span class="text-sm font-bold text-brandMaroon">Total Nilai Penjualan: Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Tanggal</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Kasir</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Obat & Merek</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">Jumlah</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-right">Harga Satuan</th>
                        <th class="py-3 px-4 font-medium text-right">Subtotal</th>
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
                            <td rowspan="{{ $transaksi->DetailTransaksi->count() }}" class="py-4 px-4 border-r border-gray-200 align-top">{{ $transaksi->User->name ?? 'Admin' }}</td>
                            @endif
                            <td class="py-4 px-4 border-r border-gray-200">
                                <span class="font-bold text-gray-900 block">{{ $detail->Obat->nama_obat ?? 'Dihapus' }}</span>
                                <span class="text-xs text-gray-500">{{ $detail->merek ?? 'Generik' }}</span>
                            </td>
                            <td class="py-4 px-4 border-r border-gray-200 text-center font-medium">{{ abs($detail->jumlah_masuk) }}</td>
                            
                            <td class="py-4 px-4 border-r border-gray-200 text-right text-gray-600">
                                Rp {{ number_format($detail->subtotal / max(abs($detail->jumlah_masuk), 1), 0, ',', '.') }}
                            </td>
                            
                            <td class="py-4 px-4 text-right font-medium text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Data laporan barang keluar tidak ada</p>
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