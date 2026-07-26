@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-black mb-6 mt-2">
    <div class="flex items-center gap-3">
        <i class="fas fa-pills text-2xl"></i>
        <h1 class="text-2xl font-semibold">Daftar Obat</h1>
    </div>
    @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
    <a href="{{ route('obat.create') }}" class="bg-[#F0FDF4] text-[#0d9488] border border-[#0d9488]/30 hover:bg-emerald-100 px-4 py-2.5 rounded-lg font-semibold flex items-center justify-center gap-2 transition-colors shadow-sm text-sm sm:text-base w-full sm:w-auto">
        <i class="fas fa-plus"></i> Tambah Obat
    </a>
    @endif
</div>

@if ($message = Session::get('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('obat.index') }}" class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode obat..." class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon">
        </div>
        <div class="w-full sm:w-auto">
            <select name="jenis_obat" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon">
                <option value="">-- Semua Jenis Obat --</option>
                @foreach($jenisList as $jenis)
                <option value="{{ $jenis }}" {{ request('jenis_obat') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full sm:w-auto">
            <select name="golongan_obat" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon">
                <option value="">-- Semua Golongan --</option>
                @foreach($golonganList as $gol)
                <option value="{{ $gol }}" {{ request('golongan_obat') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request('search') || request('jenis_obat') || request('golongan_obat'))
            <a href="{{ route('obat.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
        <span class="text-sm text-gray-700 font-medium">Daftar Master Obat</span>
        <span class="text-xs text-gray-500 font-medium">Total Obat: {{ $obats->count() }} data</span>
    </div>

    <div class="p-3 sm:p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left border-collapse min-w-[640px]">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium text-center">No</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Kode</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Nama Obat</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Golongan</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Satuan</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium text-center">Stok</th>
                        <th class="py-3 px-3 sm:px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($obats as $key => $obat)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 sm:py-4 px-3 sm:px-4 border-r border-gray-200 text-center">{{ $key + 1 }}</td>
                        <td class="py-4 px-4 border-r border-gray-200 font-semibold text-gray-800">{{ $obat->kode_obat ?? '-' }}</td>
                        
                        <td class="py-4 px-4 border-r border-gray-200 font-medium text-gray-900">
                            <div>{{ $obat->nama_obat }}</div>
                            <div class="text-xs text-gray-500 font-normal">{{ $obat->jenis_obat }}</div>
                        </td>

                        <td class="py-4 px-4 border-r border-gray-200">
                            @php
                                $gol = $obat->golongan_obat;
                                $badgeClass = match($gol) {
                                    'Obat Bebas' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'Obat Bebas Terbatas' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'Obat Keras' => 'bg-red-100 text-red-800 border-red-300',
                                    'Herbal / Jamu' => 'bg-green-100 text-green-800 border-green-300',
                                    'Psikotropika / Narkotika' => 'bg-purple-100 text-purple-800 border-purple-300',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200'
                                };
                            @endphp
                            @if($gol)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                    {{ $gol }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-xs">-</span>
                            @endif
                        </td>

                        <td class="py-4 px-4 border-r border-gray-200">{{ $obat->satuan }}</td>
                        <td class="py-4 px-4 border-r border-gray-200 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $obat->stok > 10 ? 'bg-green-100 text-green-800' : ($obat->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $obat->stok }}
                            </span>
                        </td>
                        
                        <td class="py-4 px-4 text-center">
                            <div class="flex justify-center items-center gap-3">
                                <a href="{{ route('obat.show', $obat->id) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors" title="Detail Obat">
                                    <i class="fas fa-info-circle text-lg"></i>
                                </a>

                                @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
                                <a href="{{ route('obat.edit', $obat->id) }}" class="text-brandGreen hover:text-green-600 transition" title="Edit">
                                    <i class="fas fa-edit text-lg"></i>
                                </a>
                                <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" onclick="return confirm('Yakin ingin menghapus obat ini?')" title="Hapus Obat">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Data obat tidak ada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection