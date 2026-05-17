@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between gap-3 text-black mb-6 mt-2">
    <div class="flex items-center gap-3">
        <i class="fas fa-sign-in-alt text-2xl"></i>
        <h1 class="text-2xl font-semibold">Barang Masuk</h1>
    </div>
    <a href="{{ route('barang-masuk.create') }}" class="bg-brandMaroon text-white px-4 py-2 rounded-lg hover:bg-red-800 flex items-center gap-2">
        <i class="fas fa-plus"></i> Barang Masuk Baru
    </a>
</div>

@if ($message = Session::get('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
        <span class="text-sm text-gray-700 font-medium">Total Transaksi Masuk: {{ $transaksis->whereNotNull('pemasok_id')->count() }}</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Tanggal</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Pemasok</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Total Harga</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Jumlah Item</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis->whereNotNull('pemasok_id') as $key => $transaksi)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-4 border-r border-gray-200">{{ $key + 1 }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $transaksi->Pemasok->nama_pemasok ?? '-' }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $transaksi->DetailTransaksi->count() }} item</td>
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" style="display:inline;">
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
                        <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Data barang masuk tidak ada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
