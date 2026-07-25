@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-home text-2xl"></i>
    <h1 class="text-2xl font-semibold">Dashboard</h1>
</div>

<div class="mb-3 text-[11px] font-bold text-white-500 uppercase tracking-wider">Ringkasan Finansial</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex items-center justify-between transition-transform hover:scale-[1.02]">
        <div class="space-y-2">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Omset Bulan Ini</p>
            <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-500">Total kotor hasil penjualan</p>
        </div>
        <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
            <i class="fas fa-wallet text-2xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex items-center justify-between transition-transform hover:scale-[1.02]">
        <div class="space-y-2">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Laba Bersih Bulan Ini</p>
            <h3 class="text-2xl font-bold {{ ($labaBulanIni ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($labaBulanIni ?? 0, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500">Keuntungan yang didapat bulan ini</p>
        </div>
        <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
            <i class="fas fa-chart-line text-2xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between transition-transform hover:scale-[1.02]">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aktivitas Hari Ini</p>
            <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Real-Time</span>
        </div>
        <div class="grid grid-cols-2 gap-2 border-t border-gray-100 pt-3">
            <div>
                <p class="text-[11px] text-gray-500">Omset</p>
                <p class="text-sm font-bold text-gray-800">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-[11px] text-gray-500">Laba Bersih</p>
                <p class="text-sm font-bold {{ ($labaHariIni ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    Rp {{ number_format($labaHariIni ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="mb-3 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Statistik Stok & Data Master</div>
<div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex flex-col md:flex-row items-center justify-between border border-gray-100">
    <div class="flex items-center gap-4 flex-1 w-full justify-center md:justify-start">
        <i class="fas fa-tags text-4xl text-gray-700"></i>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Merek Obat</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalMerek }}</div>
        </div>
    </div>
    <div class="hidden md:block w-px h-12 bg-gray-200 mx-6"></div>

    <div class="flex items-center gap-4 flex-1 w-full justify-center md:justify-start mt-4 md:mt-0">
        <i class="fas fa-inbox text-4xl text-brandGreen"></i>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Obat Masuk</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalObatMasuk }}</div>
        </div>
    </div>
    <div class="hidden md:block w-px h-12 bg-gray-200 mx-6"></div>

    <div class="flex items-center gap-4 flex-1 w-full justify-center md:justify-start mt-4 md:mt-0">
        <i class="fas fa-dolly text-4xl text-gray-700"></i>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Obat Keluar</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalObatKeluar }}</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-5 border border-gray-100">
        <div class="w-16 h-16 rounded-xl bg-brandGreen flex items-center justify-center text-white shadow-sm">
            <i class="fas fa-file-alt text-2xl"></i>
        </div>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Jenis Obat</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalJenisObat }}</div>
        </div>
    </div>

    @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
    <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-5 border border-gray-100">
        <div class="w-16 h-16 rounded-xl bg-gray-700 flex items-center justify-center text-white shadow-sm">
            <i class="fas fa-user text-2xl"></i>
        </div>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Pengguna</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalPengguna }}</div>
        </div>
    </div>
    @endif
</div>

<!-- TABEL EWS (EARLY WARNING SYSTEM) TERPADU -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 rounded-t-lg">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-triangle text-amber-500 text-lg"></i>
            <span class="text-sm font-bold text-gray-800">Early Warning System</span>
        </div>
        <span class="text-xs text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200 font-medium">
            Total Peringatan: {{ $ewsItems->count() }}
        </span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center border border-gray-200">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-3 border-r border-gray-200 font-medium w-12">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-left">Status Peringatan</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Kode Obat</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-left">Nama Obat</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-left">Merek</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Jenis Obat</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Stok</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-left">Keterangan</th>
                        <th class="py-3 px-4 font-medium w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($ewsItems->isEmpty())
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td colspan="9" class="py-8 text-center text-gray-500">
                            <i class="fas fa-check-circle text-emerald-500 text-3xl mb-2 block"></i>
                            <p class="font-medium text-gray-700">Semua Obat Dalam Kondisi Aman</p>
                            <p class="text-xs text-gray-400 mt-0.5">Tidak ada stok menipis maupun obat yang kedaluwarsa.</p>
                        </td>
                    </tr>
                    @else
                    @foreach($ewsItems as $index => $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-3 px-3 border-r border-gray-200 text-gray-600">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 border-r border-gray-200 text-left">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $item['badge'] }}">
                                <i class="{{ $item['icon'] }}"></i>
                                {{ $item['label'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 border-r border-gray-200 font-semibold text-gray-800">{{ $item['kode_obat'] }}</td>
                        <td class="py-3 px-4 border-r border-gray-200 text-left font-medium text-gray-900">{{ $item['nama_obat'] }}</td>
                        <td class="py-3 px-4 border-r border-gray-200 text-left font-medium text-gray-800">{{ $item['merek'] ?? 'Generik' }}</td>
                        <td class="py-3 px-4 border-r border-gray-200 text-gray-600">{{ $item['jenis_obat'] }}</td>
                        <td class="py-3 px-4 border-r border-gray-200 font-bold {{ $item['type'] === 'out_of_stock' ? 'text-rose-700 bg-rose-50/50' : ($item['type'] === 'low_stock' ? 'text-orange-600 bg-orange-50/50' : ($item['type'] === 'expired' ? 'text-red-600 bg-red-50/50' : 'text-amber-700 bg-amber-50/50')) }}">
                            {{ $item['stok'] }} pcs
                        </td>
                        <td class="py-3 px-4 border-r border-gray-200 text-left text-xs text-gray-600">
                            {{ $item['keterangan'] }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($item['obat_id'])
                            <a href="{{ route('obat.show', $item['obat_id']) }}" class="text-teal-600 hover:text-teal-800 transition" title="Lihat Detail Obat">
                                <i class="fas fa-info-circle text-lg"></i>
                            </a>
                            @else
                            <span class="text-gray-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection