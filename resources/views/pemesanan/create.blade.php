@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2 relative z-10">
    <i class="fas fa-shopping-cart text-2xl"></i>
    <h1 class="text-2xl font-semibold">Buat Pengajuan Pemesanan</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 relative z-10">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
        <span class="text-sm text-gray-700 font-medium">Form Pengajuan Surat Pemesanan Baru</span>
    </div>

    <form action="{{ route('pemesanan.store') }}" method="POST" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-6">
            
            <div class="space-y-6">
                <div>
                    <label for="pemasok_id" class="block text-sm font-bold text-gray-700 mb-2">Ditujukan Kepada (Pemasok/PBF) <span class="text-red-500">*</span></label>
                    <select id="pemasok_id" name="pemasok_id" 
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-4 text-gray-800 focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('pemasok_id') border-red-500 @enderror" 
                        required>
                        <option value="" disabled selected>-- Pilih Pemasok / PBF --</option>
                        @foreach ($pemasoks as $pemasok)
                            <option value="{{ $pemasok->id }}" {{ old('pemasok_id') == $pemasok->id ? 'selected' : '' }}>
                                {{ $pemasok->nama_pemasok }}
                            </option>
                        @endforeach
                    </select>
                    @error('pemasok_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="merek" class="block text-sm font-bold text-gray-700 mb-2">Merek Spesifik <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" id="merek" name="merek" 
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-4 text-gray-800 focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('merek') border-red-500 @enderror" 
                        placeholder="Contoh: Sanmol, Bodrex (Kosongkan jika generik)"
                        value="{{ old('merek') }}">
                    @error('merek') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="nama_obat" class="block text-sm font-bold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_obat" name="nama_obat" 
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-4 text-gray-800 focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('nama_obat') border-red-500 @enderror" 
                        placeholder="Masukkan nama obat yang ingin dipesan..."
                        value="{{ old('nama_obat') }}" required>
                    @error('nama_obat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jumlah" class="block text-sm font-bold text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" id="jumlah" name="jumlah" 
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-4 text-gray-800 focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('jumlah') border-red-500 @enderror" 
                        placeholder="Masukkan jumlah pemesanan (pcs/box)..."
                        value="{{ old('jumlah') }}" min="1" required>
                    @error('jumlah') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

        </div>

        <div class="mb-8">
            <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Alasan Pemesanan</label>
            <textarea id="keterangan" name="keterangan" rows="4"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg py-2.5 px-4 text-gray-800 focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('keterangan') border-red-500 @enderror" 
                placeholder="Tulis alasan pemesanan obat, contoh: untuk stok bulan ini..."
                >{{ old('keterangan') }}</textarea>
            @error('keterangan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
            <button type="submit" 
                class="bg-brandMaroon hover:bg-teal-800 text-white font-semibold py-2.5 px-6 rounded-lg shadow-sm transition duration-300">
                Ajukan Pemesanan
            </button>
            <a href="{{ route('pemesanan.index') }}" 
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg border border-gray-200 transition duration-300">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection