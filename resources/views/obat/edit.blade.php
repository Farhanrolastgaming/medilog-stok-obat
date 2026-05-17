@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-edit text-2xl"></i>
    <h1 class="text-2xl font-semibold">Edit Obat</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form action="{{ route('obat.update', $obat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="nama_obat" class="block text-sm font-medium text-gray-700 mb-2">Nama Obat</label>
            <input type="text" id="nama_obat" name="nama_obat" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent @error('nama_obat') border-red-500 @enderror" value="{{ old('nama_obat', $obat->nama_obat) }}" required>
            @error('nama_obat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="jenis_obat" class="block text-sm font-medium text-gray-700 mb-2">Jenis Obat</label>
            <input type="text" id="jenis_obat" name="jenis_obat" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent @error('jenis_obat') border-red-500 @enderror" value="{{ old('jenis_obat', $obat->jenis_obat) }}" required>
            @error('jenis_obat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                <input type="text" id="satuan" name="satuan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent @error('satuan') border-red-500 @enderror" value="{{ old('satuan', $obat->satuan) }}" required>
                @error('satuan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="harga_jual" class="block text-sm font-medium text-gray-700 mb-2">Harga Jual</label>
                <input type="number" id="harga_jual" name="harga_jual" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent @error('harga_jual') border-red-500 @enderror" value="{{ old('harga_jual', $obat->harga_jual) }}" required>
                @error('harga_jual') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
            <input type="number" id="stok" name="stok" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent @error('stok') border-red-500 @enderror" value="{{ old('stok', $obat->stok) }}" required>
            @error('stok') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-brandMaroon text-white px-6 py-2 rounded-lg hover:bg-red-800">
                Update
            </button>
            <a href="{{ route('obat.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
