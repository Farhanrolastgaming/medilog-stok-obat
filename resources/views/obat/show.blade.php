@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-info-circle text-2xl"></i>
    <h1 class="text-2xl font-semibold">Detail Obat</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kode Obat</label>
            <p class="text-lg font-bold text-gray-900">{{ $obat->kode_obat ?? '-' }}</p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nama Obat</label>
            <p class="text-lg font-bold text-gray-900">{{ $obat->nama_obat }}</p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Golongan Obat</label>
            <div>
                @php
                    $gol = $obat->golongan_obat;
                    $badgeClass = match($gol) {
                        'Obat Bebas' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'Obat Bebas Terbatas' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'Obat Keras' => 'bg-red-100 text-red-800 border-red-300',
                        'Herbal / Jamu' => 'bg-green-100 text-green-800 border-green-300',
                        'Psikotropika / Narkotika' => 'bg-purple-100 text-purple-800 border-purple-300',
                        default => 'bg-gray-100 text-gray-700 border-gray-300'
                    };
                    $icon = match($gol) {
                        'Obat Bebas' => '🟢',
                        'Obat Bebas Terbatas' => '🔵',
                        'Obat Keras' => '🔴',
                        'Herbal / Jamu' => '🌿',
                        'Psikotropika / Narkotika' => '🟣',
                        default => '💊'
                    };
                @endphp
                @if($gol)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-semibold border {{ $badgeClass }}">
                        <span>{{ $icon }}</span> {{ $gol }}
                    </span>
                @else
                    <span class="text-sm text-gray-400 italic">Belum diatur</span>
                @endif
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Jenis / Bentuk Sediaan</label>
            <p class="text-base font-semibold text-gray-900">{{ $obat->jenis_obat }}</p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Satuan Jual</label>
            <p class="text-base font-semibold text-gray-900">{{ $obat->satuan }}</p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Stok Keseluruhan</label>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $obat->stok > 10 ? 'bg-green-100 text-green-800' : ($obat->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                {{ $obat->stok }} {{ $obat->satuan }}
            </span>
        </div>
    </div>

    <!-- Section Komposisi dan Aturan Pakai -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg border border-gray-200 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-flask text-brandMaroon"></i>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Bahan-bahan / Komposisi</h3>
            </div>
            @if($obat->komposisi)
                <p class="text-sm text-gray-700 whitespace-pre-line bg-white p-3 rounded border border-gray-200 leading-relaxed">{{ $obat->komposisi }}</p>
            @else
                <p class="text-sm text-gray-400 italic bg-white p-3 rounded border border-gray-200">Belum ada data komposisi.</p>
            @endif
        </div>
        <div>
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-prescription-bottle-alt text-brandMaroon"></i>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Aturan Pakai & Dosis</h3>
            </div>
            @if($obat->aturan_pakai)
                <p class="text-sm text-gray-700 whitespace-pre-line bg-white p-3 rounded border border-gray-200 leading-relaxed">{{ $obat->aturan_pakai }}</p>
            @else
                <p class="text-sm text-gray-400 italic bg-white p-3 rounded border border-gray-200">Belum ada data aturan pakai.</p>
            @endif
        </div>
    </div>

    <hr class="my-6 border-gray-200">

    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Keseluruhan Stok Obat</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600 border border-gray-200 rounded-lg">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-semibold text-center">No</th>
                        <th class="px-6 py-3 font-semibold">Merek</th>
                        <th class="px-6 py-3 font-semibold">Harga Beli</th>
                        <th class="px-6 py-3 font-semibold">Harga Jual</th>
                        <th class="px-6 py-3 font-semibold text-center">Stok</th>
                        <th class="px-6 py-3 font-semibold">Kedaluwarsa</th>
                        <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($obat->stokBatches as $batch)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>

                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $batch->merek ?? 'Generik' }}
                        </td>

                        <td class="px-6 py-4">Rp {{ number_format($batch->harga_beli, 0, ',', '.') }}</td>

                        <td class="px-6 py-4">Rp {{ number_format($batch->harga_jual, 0, ',', '.') }}</td>
                        
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                {{ $batch->stok }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($batch->expired_date)->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
                            <form action="{{ route('stok-batch.destroy', $batch->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat batch stok ini?')">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 transition cursor-pointer" title="Hapus Batch Stok">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-gray-300 text-xs italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                            <i class="fas fa-box-open text-2xl mb-2 text-gray-300 block"></i>
                            Belum ada riwayat batch stok untuk obat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex gap-3 pt-4 border-t border-gray-200">
        @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
        <a href="{{ route('obat.edit', $obat->id) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition-colors flex items-center gap-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus obat ini sepenuhnya?')">
            @method('DELETE')
            @csrf
            <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition-colors flex items-center gap-2">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
        @endif
        <a href="{{ route('obat.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection