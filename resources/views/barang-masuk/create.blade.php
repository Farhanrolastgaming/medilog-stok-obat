@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-plus text-2xl"></i>
    <h1 class="text-2xl font-semibold">Tambah Barang Masuk</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form action="{{ route('barang-masuk.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="pemasok_id" class="block text-sm font-medium text-gray-700 mb-2">Pemasok</label>
                <select id="pemasok_id" name="pemasok_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent @error('pemasok_id') border-red-500 @enderror" required>
                    <option value="">-- Pilih Pemasok --</option>
                    @foreach ($pemasoks as $pemasok)
                    <option value="{{ $pemasok->id }}">{{ $pemasok->nama_pemasok }}</option>
                    @endforeach
                </select>
                @error('pemasok_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="tanggal_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi</label>
                <input type="date" id="tanggal_transaksi" name="tanggal_transaksi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent @error('tanggal_transaksi') border-red-500 @enderror" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                @error('tanggal_transaksi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Detail Barang</h3>
            <div id="items-container">
                <div class="item-row grid grid-cols-7 gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Obat</label>
                        <select name="obat_id[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent" required>
                            <option value="">-- Pilih Obat --</option>
                            @foreach ($obats as $obat)
                            <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merek</label>
                        <input type="text" name="merek[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent" placeholder="Cth: Sanmol">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" name="jumlah[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent" min="1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Beli</label>
                        <input type="number" name="harga_beli[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent" min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual</label>
                        <input type="number" name="harga_jual[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent" min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kedaluwarsa</label>
                        <input type="date" name="masa_kadaluwarsa[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-item w-full bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-4">
                <i class="fas fa-plus"></i> Tambah Item
            </button>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-brandMaroon text-white px-6 py-2 rounded-lg hover:bg-teal-800 transition">
                Simpan
            </button>
            <a href="{{ route('barang-masuk.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const newRow = container.firstElementChild.cloneNode(true);
    // Ini akan otomatis mengosongkan semua input termasuk Merek saat baris baru ditambah
    newRow.querySelectorAll('input, select').forEach(el => el.value = '');
    container.appendChild(newRow);
    attachRemoveListeners();
});

function attachRemoveListeners() {
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            if(document.querySelectorAll('.item-row').length > 1) {
                this.closest('.item-row').remove();
            } else {
                alert('Minimal harus ada 1 barang!');
            }
        });
    });
}

attachRemoveListeners();
</script>
@endsection