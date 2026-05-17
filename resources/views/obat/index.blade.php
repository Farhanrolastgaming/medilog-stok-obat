@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between gap-3 text-black mb-6 mt-2">
    <div class="flex items-center gap-3">
        <i class="fas fa-pills text-2xl"></i>
        <h1 class="text-2xl font-semibold">Daftar Obat</h1>
    </div>
    <a href="{{ route('obat.create') }}" class="bg-brandMaroon text-white px-4 py-2 rounded-lg hover:bg-red-800 flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Obat
    </a>
</div>

@if ($message = Session::get('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ $message }}
    </div>
@endif

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
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Harga Jual</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Stok</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($obats as $key => $obat)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-4 border-r border-gray-200">{{ $key + 1 }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $obat->nama_obat }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $obat->jenis_obat }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $obat->satuan }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $obat->stok > 10 ? 'bg-green-100 text-green-800' : ($obat->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $obat->stok }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('obat.edit', $obat->id) }}" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
