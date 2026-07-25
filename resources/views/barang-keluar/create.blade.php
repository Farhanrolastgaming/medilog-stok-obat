@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container .select2-selection--single {
        height: 42px !important;
        border-color: #d1d5db !important;
        border-radius: 0.5rem !important;
        padding-top: 6px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 8px !important;
    }
</style>

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
                <div class="item-row grid grid-cols-12 gap-4 mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    
                    <div class="col-span-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Obat & Merek</label>
                        <select name="obat_merek[]" class="select2-obat w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon" required>
                            <option value="">-- Ketik Nama Obat / Merek --</option>
                            @foreach ($stokTersedia as $stok)
                                @php
                                    $merekDisplay = $stok->merek ?? 'Generik';
                                    $valueGabungan = $stok->obat_id . '|' . $merekDisplay;
                                @endphp
                                <option value="{{ $valueGabungan }}">
                                    {{ $stok->obat->nama_obat }} - {{ $merekDisplay }} (Tersedia: {{ $stok->total_stok }} | Rp {{ number_format($stok->harga_jual, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Jual</label>
                        <input type="number" name="jumlah[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brandMaroon focus:border-transparent" min="1" required>
                    </div>

                    <div class="col-span-1 flex items-end">
                        <button type="button" class="remove-item w-full bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 text-sm" style="height: 42px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 mt-4">
                <i class="fas fa-plus"></i> Tambah Item
            </button>
        </div>

        @if (session('errors'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex gap-3">
            <button type="submit" class="bg-brandMaroon text-white px-6 py-2 rounded-lg hover:bg-teal-800 transition">
                Simpan Data
            </button>
            <a href="{{ route('barang-keluar.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Fungsi untuk menginisialisasi Select2
    function initSelect2() {
        $('.select2-obat').select2({
            placeholder: "-- Ketik Nama Obat / Merek --",
            allowClear: true,
            width: '100%'
        });
    }

    // Jalankan Select2 saat halaman pertama kali dimuat
    initSelect2();

    $('#add-item').click(function() {
        const container = document.getElementById('items-container');
        
        // Sebelum cloning, kita harus 'menghancurkan' fungsi Select2 sementara 
        // agar elemen hasil cloning tidak error atau berlipat ganda
        $('.select2-obat').select2('destroy');
        
        // Lakukan Cloning
        const newRow = container.firstElementChild.cloneNode(true);
        
        // Kosongkan value pada baris baru
        newRow.querySelectorAll('input').forEach(el => el.value = '');
        newRow.querySelectorAll('select').forEach(el => el.value = '');
        
        // Masukkan baris baru ke dalam container
        container.appendChild(newRow);
        
        // Bangkitkan kembali Select2 untuk semua baris (termasuk yang baru)
        initSelect2();
        
        attachRemoveListeners();
    });

    function attachRemoveListeners() {
        $('.remove-item').off('click').on('click', function() {
            if($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            } else {
                alert('Minimal harus ada 1 barang untuk dijual!');
            }
        });
    }

    attachRemoveListeners();
});
</script>
@endsection