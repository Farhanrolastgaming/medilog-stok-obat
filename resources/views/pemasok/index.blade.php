@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6 mt-2">
    <div class="flex items-center gap-3 text-black">
        <i class="fas fa-users text-2xl"></i>
        <h1 class="text-2xl font-semibold">Data Pemasok</h1>
    </div>
    <a href="{{ route('pemasok.create') }}" class="flex items-center gap-2 bg-brandGreen text-white px-4 py-2.5 rounded-lg font-medium hover:bg-green-600 transition shadow-sm">
        <i class="fas fa-plus"></i>
        Tambah Pemasok
    </a>
</div>

<!-- Success Message -->
@if (session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none'" class="ml-auto">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<!-- Data Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 rounded-t-lg">
        <div class="flex items-center gap-2">
            <i class="fas fa-database text-gray-600"></i>
            <span class="font-medium text-gray-800">Daftar Pemasok</span>
        </div>
        <span class="text-sm text-gray-600">Total: <strong>{{ count($pemasoks) }}</strong> pemasok</span>
    </div>

    <div class="overflow-x-auto">
        @if($pemasoks->isEmpty())
            <div class="p-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
                <p class="text-gray-500 mb-4">Belum ada data pemasok</p>
                <a href="{{ route('pemasok.create') }}" class="inline-flex items-center gap-2 bg-brandGreen text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                    <i class="fas fa-plus"></i>
                    Tambah Pemasok Pertama
                </a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-700 bg-gray-50 border-b border-gray-200">
                        <th class="py-3 px-6 border-r border-gray-200 font-medium text-left w-12">No</th>
                        <th class="py-3 px-6 border-r border-gray-200 font-medium text-left">Nama Pemasok</th>
                        <th class="py-3 px-6 border-r border-gray-200 font-medium text-left">Informasi Kontak</th>
                        <th class="py-3 px-6 font-medium text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemasoks as $key => $pemasok)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="py-4 px-6 border-r border-gray-200 font-medium text-gray-800">
                                {{ $key + 1 }}
                            </td>
                            <td class="py-4 px-6 border-r border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-brandGreen flex items-center justify-center text-white font-semibold text-xs">
                                        {{ substr($pemasok->nama_pemasok, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $pemasok->nama_pemasok }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 border-r border-gray-200">
                                <p class="text-gray-600 whitespace-pre-wrap text-xs">{{ Str::limit($pemasok->info_kontak, 50) }}</p>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('pemasok.edit', $pemasok->id) }}" class="text-brandGreen hover:text-green-600 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('pemasok.destroy', $pemasok->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pemasok ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
