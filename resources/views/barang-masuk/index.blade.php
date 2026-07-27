@extends('layouts.app')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-black mb-6 mt-2">
    <div class="flex items-center gap-3">
        <i class="fas fa-sign-in-alt text-2xl"></i>
        <h1 class="text-2xl font-semibold">Barang Masuk</h1>
    </div>
    
    @if(auth()->user() && in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
    <a href="{{ route('barang-masuk.create') }}" class="bg-[#F0FDF4] text-[#0d9488] border border-[#0d9488]/30 hover:bg-emerald-100 px-4 py-2.5 rounded-lg font-semibold flex items-center justify-center gap-2 transition-colors shadow-sm text-sm sm:text-base w-full sm:w-auto">
        <i class="fas fa-plus"></i> Barang Masuk Baru
    </a>
    @endif
</div>

@if ($message = Session::get('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
        <span class="text-sm text-gray-700 font-medium">Total Transaksi Masuk: {{ $transaksis->whereNotNull('pemasok_id')->count() }}</span>
    </div>

    <div class="p-3 sm:p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left border-collapse min-w-[640px]">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium text-center">No</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Tanggal</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Pemasok</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium">Total Harga</th>
                        <th class="py-3 px-3 sm:px-4 border-r border-gray-200 font-medium text-center">Jumlah Item</th>
                        <th class="py-3 px-3 sm:px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis->whereNotNull('pemasok_id') as $key => $transaksi)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 sm:py-4 px-3 sm:px-4 border-r border-gray-200 text-center">{{ $key + 1 }}</td>
                        <td class="py-3 sm:py-4 px-3 sm:px-4 border-r border-gray-200">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                        <td class="py-3 sm:py-4 px-3 sm:px-4 border-r border-gray-200 font-medium text-gray-900">{{ $transaksi->Pemasok->nama_pemasok ?? '-' }}</td>
                        <td class="py-3 sm:py-4 px-3 sm:px-4 border-r border-gray-200">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        <td class="py-3 sm:py-4 px-3 sm:px-4 border-r border-gray-200 text-center">{{ $transaksi->DetailTransaksi->count() }} item</td>
                        <td class="py-3 sm:py-4 px-3 sm:px-4 text-center">
                            <div class="flex justify-center items-center gap-3">
                                <button type="button" onclick="openModal('modal-{{ $transaksi->id }}')" class="text-indigo-600 hover:text-indigo-800 transition-colors flex items-center justify-center p-1.5 rounded-md hover:bg-indigo-50" title="Detail Transaksi">
                                    <i class="fas fa-info-circle text-lg"></i>
                                </button>

                                <form action="{{ route('barang-masuk.destroy', $transaksi->id) }}" method="POST" class="inline m-0 flex items-center">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors flex items-center justify-center p-1.5 rounded-md hover:bg-red-50" onclick="return confirm('Yakin ingin menghapus transaksi ini?')" title="Hapus Transaksi">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-lg font-medium">Data barang masuk tidak ada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('modals')
    @foreach ($transaksis->whereNotNull('pemasok_id') as $transaksi)
    <div id="modal-{{ $transaksi->id }}" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-60 flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm transition-opacity">
        
        <div class="bg-[#e5e5e5] w-full max-w-4xl max-h-[92vh] overflow-y-auto rounded-2xl shadow-2xl p-4 sm:p-8 relative">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-300 pb-4 mb-6 gap-3">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Detail Transaksi</h2>
                <a href="{{ route('barang-masuk.cetak', $transaksi->id) }}" target="_blank" class="bg-brandMaroon text-white px-4 py-2 rounded-lg text-xs sm:text-sm font-medium hover:bg-teal-800 flex items-center justify-center gap-2 transition-colors w-full sm:w-auto shadow-sm">
                    <i class="fas fa-print"></i> Cetak Transaksi
                </a>
            </div>
            
            <div class="grid grid-cols-2 gap-y-4 sm:gap-y-6 gap-x-4 sm:gap-x-8 mb-6 text-sm sm:text-base">
                <div>
                    <p class="text-xs sm:text-sm font-bold text-gray-900 mb-1">NO. TRANSAKSI</p>
                    <p class="text-gray-700 font-semibold text-base sm:text-lg">{{ $transaksi->kode_transaksi }}</p>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-bold text-gray-900 mb-1">ITEM</p>
                    <p class="text-gray-700 font-semibold text-base sm:text-lg">{{ $transaksi->DetailTransaksi->count() }} Item</p>
                </div>

                <div>
                    <p class="text-xs sm:text-sm font-bold text-gray-900 mb-1">TANGGAL</p>
                    <p class="text-gray-700 text-sm sm:text-base">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d - m - Y') }}</p>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-bold text-gray-900 mb-1">JUMLAH TOTAL</p>
                    <p class="text-gray-700 text-sm sm:text-base">{{ $transaksi->DetailTransaksi->sum('jumlah_masuk') }} pcs</p>
                </div>

                <div class="col-span-2">
                    <p class="text-xs sm:text-sm font-bold text-gray-900 mb-1">NAMA PEMASOK</p>
                    <p class="text-gray-700 font-medium text-sm sm:text-base">{{ $transaksi->pemasok ? $transaksi->pemasok->nama_pemasok : '-' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-300 overflow-x-auto mb-6">
                <table class="w-full text-xs sm:text-sm text-center text-gray-800 border-collapse min-w-[550px]">
                    <thead class="font-bold bg-gray-50 border-b border-gray-300 text-gray-700">
                        <tr>
                            <th class="py-2.5 px-2 border-r border-gray-300">No</th>
                            <th class="py-2.5 px-3 border-r border-gray-300 text-left">Obat</th>
                            <th class="py-2.5 px-3 border-r border-gray-300 text-left">Jenis</th>
                            <th class="py-2.5 px-2 border-r border-gray-300">Stok</th>
                            <th class="py-2.5 px-3 border-r border-gray-300">Harga Beli</th>
                            <th class="py-2.5 px-3 border-r border-gray-300">Subtotal</th>
                            <th class="py-2.5 px-3">Tgl Kadaluarsa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->DetailTransaksi as $index => $detail)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-2.5 px-2 border-r border-gray-200">{{ $index + 1 }}</td>
                            <td class="py-2.5 px-3 border-r border-gray-200 text-left">
                                <span class="font-medium text-gray-900">{{ $detail->obat->nama_obat ?? 'Obat Telah Dihapus' }}</span><br>
                                <span class="text-xs text-gray-500">{{ $detail->merek ?? 'Generik' }}</span>
                            </td>
                            <td class="py-2.5 px-3 border-r border-gray-200 text-left">{{ $detail->obat->jenis_obat ?? '-' }}</td>
                            <td class="py-2.5 px-2 border-r border-gray-200">{{ $detail->jumlah_masuk }}</td>
                            <td class="py-2.5 px-3 border-r border-gray-200">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-3 border-r border-gray-200">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-3">
                                {{ $detail->masa_kadaluwarsa ? \Carbon\Carbon::parse($detail->masa_kadaluwarsa)->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeModal(this)" class="bg-[#982b36] hover:bg-red-800 text-white px-6 sm:px-8 py-2.5 rounded-lg font-medium transition-colors shadow-sm text-sm sm:text-base">
                    Kembali
                </button>
            </div>

        </div>
    </div>
    @endforeach
@endpush

<script>
    function openModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(button) {
        const modal = button.closest('.fixed');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('fixed')) {
            e.target.classList.add('hidden');
        }
    });
</script>