@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-user-edit text-2xl"></i>
    <h1 class="text-2xl font-semibold">Edit Data Pemasok</h1>
</div>

<div class="grid grid-cols-3 gap-6">
    <!-- Left Sidebar Info -->
    <div class="col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 sticky top-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-lg bg-brandGreen flex items-center justify-center text-white">
                    <i class="fas fa-info-circle text-lg"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Informasi</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Perbarui formulir di samping untuk mengubah data pemasok dalam sistem MEDILOG.
            </p>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex gap-2">
                    <i class="fas fa-check text-brandGreen mt-0.5"></i>
                    <span>Pastikan nama pemasok sudah benar</span>
                </div>
                <div class="flex gap-2">
                    <i class="fas fa-check text-brandGreen mt-0.5"></i>
                    <span>Perbarui informasi kontak jika diperlukan</span>
                </div>
                <div class="flex gap-2">
                    <i class="fas fa-check text-brandGreen mt-0.5"></i>
                    <span>Semua field wajib diisi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
            <form action="{{ route('pemasok.update', $pemasok->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Pemasok -->
                <div class="form-group">
                    <label for="nama_pemasok" class="block text-sm font-semibold text-gray-800 mb-2">
                        Nama Pemasok
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_pemasok"
                        id="nama_pemasok"
                        placeholder="Contoh: PT Farmasi Indonesia"
                        class="w-full px-4 py-3 border @error('nama_pemasok') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen focus:border-transparent transition text-sm"
                        value="{{ old('nama_pemasok', $pemasok->nama_pemasok) }}"
                        required
                    />
                    @error('nama_pemasok')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Info Kontak -->
                <div class="form-group">
                    <label for="info_kontak" class="block text-sm font-semibold text-gray-800 mb-2">
                        Informasi Kontak
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea
                        name="info_kontak"
                        id="info_kontak"
                        rows="4"
                        placeholder="Contoh: No. Telp: 021-1234567&#10;Email: info@farmasi.com&#10;Alamat: Jl. Merdeka No. 123"
                        class="w-full px-4 py-3 border @error('info_kontak') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen focus:border-transparent transition text-sm resize-none"
                        required
                    >{{ old('info_kontak', $pemasok->info_kontak) }}</textarea>
                    @error('info_kontak')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <a
                        href="{{ route('pemasok.index') }}"
                        class="flex-1 px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="flex-1 px-6 py-3 bg-brandGreen rounded-lg font-medium text-white hover:bg-green-600 transition flex items-center justify-center gap-2 shadow-sm"
                    >
                        <i class="fas fa-save"></i>
                        Perbarui Pemasok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memperbarui...';
        });
    });
</script>
@endsection
