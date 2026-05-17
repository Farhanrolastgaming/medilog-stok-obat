@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-plus text-2xl"></i>
    <h1 class="text-2xl font-semibold">Tambah Barang Keluar</h1>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form action="{{ route('barang-keluar.store') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="tanggal_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi</label>
            <input type="date" id="tanggal_transaksi" name="tanggal_transaksi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent @error('tanggal_transaksi') border-red-500 @enderror" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
            @error('tanggal_transaksi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Detail Barang</h3>
            <div id="items-container">
                <div class="item-row grid grid-cols-3 gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Obat</label>
                        <select name="obat_id[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent" required>
                            <option value="">-- Pilih Obat --</option>
                            @foreach ($obats as $obat)
                            <option value="{{ $obat->id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" name="jumlah[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent" min="1" required>
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

        @if ($errors->has('jumlah'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ $errors->first('jumlah') }}
            </div>
        @endif

        <div class="flex gap-3">
            <button type="submit" class="bg-brandMaroon text-white px-6 py-2 rounded-lg hover:bg-red-800">
                Simpan
            </button>
            <a href="{{ route('barang-keluar.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const newRow = container.firstElementChild.cloneNode(true);
    newRow.querySelectorAll('input, select').forEach(el => el.value = '');
    container.appendChild(newRow);
    attachRemoveListeners();
});

function attachRemoveListeners() {
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.item-row').remove();
        });
    });
}

attachRemoveListeners();
</script>
@endsection
