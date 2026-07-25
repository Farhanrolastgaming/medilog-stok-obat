@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-exclamation-circle text-2xl"></i>
    <h1 class="text-2xl font-semibold">Form Pelaporan Obat Rusak / Retur</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-4xl">
    <form action="{{ route('obat-rusak.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Pilih Obat -->
            <div>
                <label for="obat_id" class="block text-sm font-semibold text-gray-800 mb-2">
                    Pilih Obat <span class="text-red-500">*</span>
                </label>
                <select id="obat_id" name="obat_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandGreen focus:border-transparent text-sm @error('obat_id') border-red-500 @enderror" required>
                    <option value="">-- Pilih Obat --</option>
                    @foreach($obats as $o)
                        <option value="{{ $o->id }}" {{ old('obat_id') == $o->id ? 'selected' : '' }}>
                            [{{ $o->kode_obat ?? '-' }}] {{ $o->nama_obat }} (Total Stok: {{ $o->stok }} {{ $o->satuan }})
                        </option>
                    @endforeach
                </select>
                @error('obat_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Pilih Merek / Batch Stok -->
            <div>
                <label for="stok_batch_id" class="block text-sm font-semibold text-gray-800 mb-2">
                    Pilih Merek / Batch Stok <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                </label>
                <select id="stok_batch_id" name="stok_batch_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandGreen focus:border-transparent text-sm">
                    <option value="">-- Pilih Batch Stok --</option>
                    @foreach($obats as $o)
                        @foreach($o->stokBatches as $batch)
                            <option value="{{ $batch->id }}" data-obat="{{ $o->id }}" class="batch-option hidden" {{ old('stok_batch_id') == $batch->id ? 'selected' : '' }}>
                                Merek: {{ $batch->merek ?: 'Generik' }} | Stok Batch: {{ $batch->stok }} | Exp: {{ $batch->expired_date ? \Carbon\Carbon::parse($batch->expired_date)->format('d-m-Y') : '-' }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Jumlah Rusak -->
            <div>
                <label for="jumlah" class="block text-sm font-semibold text-gray-800 mb-2">
                    Jumlah Barang Rusak / Retur <span class="text-red-500">*</span>
                </label>
                <input type="number" id="jumlah" name="jumlah" min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandGreen focus:border-transparent text-sm @error('jumlah') border-red-500 @enderror" value="{{ old('jumlah', 1) }}" required>
                @error('jumlah') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Tanggal Lapor -->
            <div>
                <label for="tanggal_lapor" class="block text-sm font-semibold text-gray-800 mb-2">
                    Tanggal Laporan <span class="text-red-500">*</span>
                </label>
                <input type="date" id="tanggal_lapor" name="tanggal_lapor" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandGreen focus:border-transparent text-sm @error('tanggal_lapor') border-red-500 @enderror" value="{{ old('tanggal_lapor', date('Y-m-d')) }}" required>
                @error('tanggal_lapor') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Alasan / Penyebab Kerusakan -->
        <div class="mb-6">
            <label for="alasan" class="block text-sm font-semibold text-gray-800 mb-2">
                Alasan / Penyebab Kerusakan <span class="text-red-500">*</span>
            </label>
            <select id="alasan" name="alasan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandGreen focus:border-transparent text-sm @error('alasan') border-red-500 @enderror" required>
                <option value="">-- Pilih Alasan Kerusakan --</option>
                <option value="Rusak saat Pengiriman Pemasok" {{ old('alasan') == 'Rusak saat Pengiriman Pemasok' ? 'selected' : '' }}>📦 Rusak saat Pengiriman Pemasok (Retur PBF)</option>
                <option value="Kemasan Pecah / Bocor" {{ old('alasan') == 'Kemasan Pecah / Bocor' ? 'selected' : '' }}>💔 Kemasan Pecah / Bocor / Rusak Fisik</option>
                <option value="Rusak saat Pengiriman ke Pembeli" {{ old('alasan') == 'Rusak saat Pengiriman ke Pembeli' ? 'selected' : '' }}>🚚 Rusak saat Pengiriman ke Pembeli / Pasien</option>
                <option value="Cacat Pabrik" {{ old('alasan') == 'Cacat Pabrik' ? 'selected' : '' }}>⚠️ Cacat Produksi Pabrik</option>
                <option value="Kedaluwarsa / Rusak di Gudang" {{ old('alasan') == 'Kedaluwarsa / Rusak di Gudang' ? 'selected' : '' }}>🕒 Kedaluwarsa / Rusak Penyimpanan Gudang</option>
                <option value="Lainnya" {{ old('alasan') == 'Lainnya' ? 'selected' : '' }}>❓ Lainnya</option>
            </select>
            @error('alasan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Keterangan Tambahan -->
        <div class="mb-8">
            <label for="keterangan" class="block text-sm font-semibold text-gray-800 mb-2">
                Catatan / Keterangan Tambahan
            </label>
            <textarea id="keterangan" name="keterangan" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandGreen focus:border-transparent text-sm" placeholder="Tuliskan nomor resi / detail kerusakan jika ada...">{{ old('keterangan') }}</textarea>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
            <button type="submit" class="bg-brandMaroon text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-teal-800 transition duration-300 shadow-sm">
                Simpan Data
            </button>
            <a href="{{ route('obat-rusak.index') }}" class="bg-gray-100 text-gray-700 font-semibold px-6 py-2.5 rounded-lg border border-gray-200 hover:bg-gray-200 transition duration-300">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const obatSelect = document.getElementById('obat_id');
        const batchSelect = document.getElementById('stok_batch_id');
        const batchOptions = document.querySelectorAll('.batch-option');

        function filterBatchOptions() {
            const selectedObatId = obatSelect.value;
            batchSelect.value = "";

            batchOptions.forEach(opt => {
                if (opt.getAttribute('data-obat') === selectedObatId) {
                    opt.classList.remove('hidden');
                } else {
                    opt.classList.add('hidden');
                }
            });
        }

        obatSelect.addEventListener('change', filterBatchOptions);
        filterBatchOptions();
    });
</script>
@endsection
