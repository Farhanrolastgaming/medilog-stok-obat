@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-file-alt text-2xl"></i>
    <h1 class="text-2xl font-semibold">Laporan Stok Obat</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        <i class="fas fa-print"></i> Cetak Laporan
    </button>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
        <span class="text-sm text-gray-700 font-medium">Total Obat: {{ $obats->count() }}</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Nama Obat</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Jenis</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Satuan</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Stok</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Harga Jual</th>
                        <th class="py-3 px-4 font-medium">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalNilaiStok = 0; @endphp
                    @forelse ($obats as $key => $obat)
                    @php $nilaiStok = $obat->stok * $obat->harga_jual; $totalNilaiStok += $nilaiStok; @endphp
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-4 border-r border-gray-200">{{ $key + 1 }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $obat->nama_obat }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $obat->jenis_obat }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $obat->satuan }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $obat->stok > 10 ? 'bg-green-100 text-green-800' : ($obat->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $obat->stok }}
                            </span>
                        </td>
                        <td class="py-4 px-4 border-r border-gray-200">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                        <td class="py-4 px-4">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Data obat tidak ada</p>
                        </td>
                    </tr>
                    @endforelse
                    @if ($obats->count() > 0)
                    <tr class="border-t-2 border-gray-300 bg-gray-50">
                        <td colspan="6" class="py-4 px-4 font-bold text-right">Total Nilai Stok:</td>
                        <td class="py-4 px-4 font-bold">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        button, .no-print { display: none; }
    }
</style>
@endsection
