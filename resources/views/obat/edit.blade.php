@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-edit text-2xl"></i>
    <h1 class="text-2xl font-semibold">Edit Obat</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

    <form action="{{ route('obat.update', $obat->id) }}" method="POST" class="p-2">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-6">
            
            <div class="space-y-6">
                <div>
                    <label for="kode_obat" class="block text-sm font-bold text-gray-700 mb-2">Kode Obat <span class="text-red-500">*</span></label>
                    <input type="text" id="kode_obat" name="kode_obat" 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('kode_obat') border-red-500 @enderror" 
                        value="{{ old('kode_obat', $obat->kode_obat) }}" 
                        placeholder="Contoh: OBT-001" required>
                    @error('kode_obat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="jenis_obat" class="block text-sm font-bold text-gray-700 mb-2">Jenis / Bentuk Sediaan <span class="text-red-500">*</span></label>
                    <input type="text" id="jenis_obat" name="jenis_obat" 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('jenis_obat') border-red-500 @enderror" 
                        value="{{ old('jenis_obat', $obat->jenis_obat) }}" 
                        placeholder="Contoh: Tablet, Sirup, Kapsul, Salep" required>
                    @error('jenis_obat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="golongan_obat" class="block text-sm font-bold text-gray-700 mb-2">Golongan Obat</label>
                    <select id="golongan_obat" name="golongan_obat" 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('golongan_obat') border-red-500 @enderror">
                        <option value="">-- Pilih Golongan Obat --</option>
                        <option value="Obat Bebas" {{ old('golongan_obat', $obat->golongan_obat) == 'Obat Bebas' ? 'selected' : '' }}>🟢 Obat Bebas</option>
                        <option value="Obat Bebas Terbatas" {{ old('golongan_obat', $obat->golongan_obat) == 'Obat Bebas Terbatas' ? 'selected' : '' }}>🔵 Obat Bebas Terbatas</option>
                        <option value="Obat Keras" {{ old('golongan_obat', $obat->golongan_obat) == 'Obat Keras' ? 'selected' : '' }}>🔴 Obat Keras</option>
                        <option value="Herbal / Jamu" {{ old('golongan_obat', $obat->golongan_obat) == 'Herbal / Jamu' ? 'selected' : '' }}>🌿 Herbal / Jamu</option>
                        <option value="Psikotropika / Narkotika" {{ old('golongan_obat', $obat->golongan_obat) == 'Psikotropika / Narkotika' ? 'selected' : '' }}>🟣 Psikotropika / Narkotika</option>
                    </select>
                    @error('golongan_obat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="nama_obat" class="block text-sm font-bold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_obat" name="nama_obat" 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('nama_obat') border-red-500 @enderror" 
                        value="{{ old('nama_obat', $obat->nama_obat) }}" 
                        placeholder="Masukkan nama lengkap obat..." required>
                    @error('nama_obat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="satuan" class="block text-sm font-bold text-gray-700 mb-2">Satuan Jual <span class="text-red-500">*</span></label>
                    <input type="text" id="satuan" name="satuan" 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('satuan') border-red-500 @enderror" 
                        value="{{ old('satuan', $obat->satuan) }}" 
                        placeholder="Contoh: Pcs, Box, Strip, Botol" required>
                    @error('satuan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-8">
            <div>
                <label for="komposisi" class="block text-sm font-bold text-gray-700 mb-2">Bahan-bahan / Komposisi</label>
                <textarea id="komposisi" name="komposisi" rows="3"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('komposisi') border-red-500 @enderror"
                    placeholder="Contoh: Paracetamol 500 mg, Caffeine 50 mg">{{ old('komposisi', $obat->komposisi) }}</textarea>
                @error('komposisi') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="aturan_pakai" class="block text-sm font-bold text-gray-700 mb-2">Aturan Pakai & Dosis</label>
                <textarea id="aturan_pakai" name="aturan_pakai" rows="3"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-brandMaroon focus:border-transparent transition duration-200 @error('aturan_pakai') border-red-500 @enderror"
                    placeholder="Contoh: Dewasa: 3x sehari 1 tablet sesudah makan">{{ old('aturan_pakai', $obat->aturan_pakai) }}</textarea>
                @error('aturan_pakai') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
            <button type="submit" 
                class="bg-brandMaroon hover:bg-teal-800 text-white font-semibold py-2.5 px-6 rounded-lg shadow-sm transition duration-300">
                Simpan Perubahan
            </button>
            <a href="{{ route('obat.index') }}" 
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg border border-gray-200 transition duration-300">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection