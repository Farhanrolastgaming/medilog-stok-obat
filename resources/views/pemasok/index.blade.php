@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-black mb-6 mt-2 relative z-10">
    <div class="flex items-center gap-3">
        <i class="fas fa-truck-loading text-2xl"></i>
        <h1 class="text-2xl font-semibold">Data Pemasok / Distributor</h1>
    </div>
    
    @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
    <a href="{{ route('pemasok.create') }}" class="bg-[#F0FDF4] text-[#0d9488] border border-[#0d9488]/30 hover:bg-emerald-100 px-4 py-2.5 rounded-lg font-semibold flex items-center justify-center gap-2 transition-colors shadow-sm text-sm sm:text-base w-full sm:w-auto">
        <i class="fas fa-plus"></i> Tambah Pemasok
    </a>
    @endif
</div>

@if ($message = Session::get('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm relative z-10">
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm relative z-10">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden relative z-10">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
        <div class="flex items-center gap-2 text-gray-700">
            <span class="text-sm font-medium">Daftar PBF / Pemasok Obat</span>
        </div>
        <span class="text-xs text-gray-500 font-medium">Total: {{ $pemasoks->count() }} Pemasok</span>
    </div>

    <div class="p-3 sm:p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left border-collapse min-w-[640px]">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium w-12 text-center">No</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Nama Pemasok & Sales (PIC)</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Kontak (Telp / WA / Email)</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Alamat & Kota</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Rekening Bank</th>
                        @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
                        <th class="py-3 px-3 sm:px-4 font-medium text-center w-28">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pemasoks as $key => $pemasok)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="py-3 sm:py-4 px-3 sm:px-4 border-r border-gray-200 text-center">{{ $key + 1 }}</td>
                        
                        <!-- Nama & PIC -->
                        <td class="py-4 px-4 border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-green-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                                    {{ strtoupper(substr($pemasok->nama_pemasok, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $pemasok->nama_pemasok }}</div>
                                    @if($pemasok->nama_pic)
                                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                            <i class="fas fa-user-tie text-gray-400"></i> PIC: {{ $pemasok->nama_pic }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Kontak -->
                        <td class="py-4 px-4 border-r border-gray-200">
                            @if($pemasok->telepon)
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $pemasok->telepon);
                                    if(str_starts_with($cleanPhone, '0')) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                <div class="mb-1">
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-2 py-0.5 rounded font-medium transition" title="Kirim Pesan WhatsApp">
                                        <i class="fab fa-whatsapp text-sm"></i> {{ $pemasok->telepon }}
                                    </a>
                                </div>
                            @endif
                            @if($pemasok->email)
                                <div class="text-xs text-gray-600 flex items-center gap-1">
                                    <i class="fas fa-envelope text-gray-400"></i> {{ $pemasok->email }}
                                </div>
                            @endif
                            @if(!$pemasok->telepon && !$pemasok->email && $pemasok->info_kontak)
                                <p class="text-xs text-gray-600">{{ Str::limit($pemasok->info_kontak, 40) }}</p>
                            @endif
                            @if(!$pemasok->telepon && !$pemasok->email && !$pemasok->info_kontak)
                                <span class="text-xs text-gray-400 italic">-</span>
                            @endif
                        </td>

                        <!-- Alamat & Kota -->
                        <td class="py-4 px-4 border-r border-gray-200">
                            @if($pemasok->kota)
                                <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-800 font-semibold rounded text-xs mb-1">
                                    📍 {{ $pemasok->kota }}
                                </span>
                            @endif
                            @if($pemasok->alamat)
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $pemasok->alamat }}</p>
                            @elseif(!$pemasok->kota)
                                <span class="text-xs text-gray-400 italic">-</span>
                            @endif
                        </td>

                        <!-- Rekening Bank -->
                        <td class="py-4 px-4 border-r border-gray-200">
                            @if($pemasok->no_rekening)
                                <div class="text-xs font-semibold text-gray-800 flex items-center gap-1">
                                    <i class="fas fa-university text-brandMaroon"></i> {{ $pemasok->no_rekening }}
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">-</span>
                            @endif
                        </td>

                        @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('pemasok.edit', $pemasok->id) }}" class="text-green-600 hover:text-green-800 transition" title="Edit Pemasok">
                                    <i class="fas fa-edit text-lg"></i>
                                </a>
                                <form action="{{ route('pemasok.destroy', $pemasok->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemasok ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus Pemasok">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']) ? '6' : '5' }}" class="py-12 px-4 text-center text-gray-500">
                            <i class="fas fa-boxes text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum ada data pemasok</p>
                            <p class="text-sm mt-1">Data master rekanan PBF/Pemasok akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection