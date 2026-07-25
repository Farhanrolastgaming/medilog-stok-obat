@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between gap-3 text-black mb-6 mt-2">
    <div class="flex items-center gap-3">
        <i class="fas fa-exclamation-triangle text-2xl"></i>
        <h1 class="text-2xl font-semibold">Retur & Pencatatan Obat Rusak</h1>
    </div>
    <a href="{{ route('obat-rusak.create') }}" class="bg-[#F0FDF4] text-[#0d9488] border border-[#0d9488]/30 hover:bg-emerald-100 px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition duration-200 shadow-sm">
        <i class="fas fa-plus"></i> Laporkan Obat Rusak
    </a>
</div>

@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Laporan Rusak</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $listRusak->count() }} Laporan</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
            <i class="fas fa-clipboard-list"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Barang Rusak/Retur</p>
            <h3 class="text-2xl font-bold text-red-600 mt-1">{{ $totalBarangRusak }} pcs</h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-xl">
            <i class="fas fa-boxes"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Penyesuaian Stok</p>
            <h3 class="text-base font-bold text-emerald-600 mt-1 flex items-center gap-1.5">
                <i class="fas fa-sync-alt"></i> Otomatis Terpotong
            </h3>
        </div>
        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
            <i class="fas fa-check-double"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
        <span class="text-sm text-gray-700 font-medium">Riwayat Penyesuaian Stok Obat Rusak & Retur</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-200">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center w-12">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Tanggal</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Jenis & Kode Obat</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Merek</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">Jumlah Rusak</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Penyebab Retur</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Keterangan Tambahan</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Pelapor</th>
                        <th class="py-3 px-4 font-medium text-center w-16">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listRusak as $index => $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-4 px-4 border-r border-gray-200 text-center text-gray-600">{{ $index + 1 }}</td>
                        <td class="py-4 px-4 border-r border-gray-200 text-gray-800 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->tanggal_lapor)->format('d - m - Y') }}
                        </td>
                        <td class="py-4 px-4 border-r border-gray-200">
                            <div class="font-bold text-gray-900">{{ $item->obat->nama_obat ?? 'Obat Dihapus' }}</div>
                            <div class="text-xs text-gray-500">[{{ $item->obat->kode_obat ?? '-' }}]</div>
                        </td>
                        <td class="py-4 px-4 border-r border-gray-200 font-medium text-gray-800">
                            {{ $item->stokBatch->merek ?? 'Generik' }}
                            @if($item->stokBatch && $item->stokBatch->expired_date)
                                <div class="text-xs text-gray-400">Exp: {{ \Carbon\Carbon::parse($item->stokBatch->expired_date)->format('d-m-Y') }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4 border-r border-gray-200 text-center font-bold text-red-600 bg-red-50/40">
                            {{ $item->jumlah }} {{ $item->obat->satuan ?? 'pcs' }}
                        </td>
                        <td class="py-4 px-4 border-r border-gray-200">
                            @php
                                $badgeClass = match($item->alasan) {
                                    'Rusak saat Pengiriman Pemasok' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'Kemasan Pecah / Bocor' => 'bg-red-100 text-red-800 border-red-200',
                                    'Rusak saat Pengiriman ke Pembeli' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'Cacat Pabrik' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                };
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                {{ $item->alasan }}
                            </span>
                        </td>
                        <td class="py-4 px-4 border-r border-gray-200 text-xs text-gray-600 max-w-xs truncate">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                        <td class="py-4 px-4 border-r border-gray-200 text-xs text-gray-700">
                            {{ $item->user->name ?? 'Sistem' }}
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('obat-rusak.cetak', $item->id) }}" target="_blank" class="text-teal-600 hover:text-teal-800 transition" title="Cetak Surat Pengantar Retur">
                                    <i class="fas fa-print text-lg"></i>
                                </a>
                                @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
                                <form action="{{ route('obat-rusak.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan laporan ini? Stok akan dikembalikan otomatis.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Batalkan & Kembalikan Stok">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300 block"></i>
                            <p class="text-lg font-medium text-gray-700">Belum Ada Pelaporan Obat Rusak / Retur</p>
                            <p class="text-xs text-gray-400 mt-1">Semua persediaan obat dalam kondisi utuh dan aman.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
