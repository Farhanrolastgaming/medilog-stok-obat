@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between gap-3 text-black mb-6 mt-2">
    <div class="flex items-center gap-3">
        <i class="fas fa-sign-out-alt text-2xl"></i>
        <h1 class="text-2xl font-semibold">Barang Keluar</h1>
    </div>
    <a href="{{ route('barang-keluar.create') }}" class="bg-[#F0FDF4] text-[#0d9488] border border-[#0d9488]/30 hover:bg-emerald-100 px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition shadow-sm">
        <i class="fas fa-plus"></i> Barang Keluar Baru
    </a>
</div>

@if ($message = Session::get('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
        <span class="text-sm text-gray-700 font-medium">Total Transaksi Keluar: {{ $transaksis->whereNull('pemasok_id')->count() }}</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Tanggal</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Total Harga</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">Jumlah Item</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis->whereNull('pemasok_id') as $key => $transaksi)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-4 border-r border-gray-200 text-center">{{ $key + 1 }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d - m - Y') }}</td>
                        <td class="py-4 px-4 border-r border-gray-200 font-medium text-gray-900">Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        <td class="py-4 px-4 border-r border-gray-200 text-center">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $transaksi->DetailTransaksi->count() }} item</span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <button type="button" onclick="openModal('modal-{{ $transaksi->id }}')" class="text-blue-600 hover:text-blue-800 mr-3" title="Detail Transaksi">
                                <i class="fas fa-info-circle text-lg"></i>
                            </button>

                            <form action="{{ route('barang-keluar.destroy', $transaksi->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin membatalkan transaksi ini? Stok akan dikembalikan otomatis.')" title="Hapus">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum ada transaksi barang keluar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach ($transaksis->whereNull('pemasok_id') as $transaksi)
    <div id="modal-{{ $transaksi->id }}" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-60 flex items-center justify-center backdrop-blur-sm transition-opacity">
        
        <div class="bg-[#e5e5e5] w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl p-8 relative">
            
            <div class="flex justify-between items-start mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Detail Transaksi</h2>
                
                <a href="{{ route('barang-keluar.cetak', $transaksi->id) }}" target="_blank" class="bg-brandMaroon text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-800 flex items-center gap-2 transition-colors">
                    <i class="fas fa-print"></i> Cetak Transaksi
                </a>
            </div>
            <div class="grid grid-cols-2 gap-y-6 gap-x-8 mb-8">

                <div>
                    <p class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-1">NO. TRANSAKSI</p>
                    <p class="text-gray-800 text-sm">{{ $transaksi->kode_transaksi }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-1">ITEM</p>
                    <p class="text-gray-800 text-sm">{{ $transaksi->DetailTransaksi->count() }} Item</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-1">TANGGAL</p>
                    <p class="text-gray-800 text-sm">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d - m - Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-1">JUMLAH</p>
                    <p class="text-gray-800 text-sm">{{ abs($transaksi->DetailTransaksi->sum('jumlah_masuk')) }} pcs</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-1">KASIR</p>
                    <p class="text-gray-800 text-sm">{{ $transaksi->User->name ?? 'Admin' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-900 font-bold border-b border-gray-200">
                            <tr>
                                <th class="py-4 px-5">No</th>
                                <th class="py-4 px-5">Obat</th>
                                <th class="py-4 px-5 text-center">Total Item</th>
                                <th class="py-4 px-5">Harga Satuan</th>
                                <th class="py-4 px-5">Total Harga</th>
                                <th class="py-4 px-5">Tanggal Kadaluarsa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi->DetailTransaksi as $index => $detail)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                <td class="py-4 px-5">{{ $index + 1 }}</td>
                                <td class="py-4 px-5">
                                    <span class="font-bold text-gray-900 block">{{ $detail->obat->nama_obat ?? 'Dihapus' }}</span>
                                    <span class="text-gray-500 text-xs">{{ $detail->merek ?? 'Generik' }}</span>
                                </td>
                                <td class="py-4 px-5 text-center">{{ abs($detail->jumlah_masuk) }}</td>
                                <td class="py-4 px-5">Rp. {{ number_format($detail->subtotal / max(abs($detail->jumlah_masuk), 1), 0, ',', '.') }}</td>
                                <td class="py-4 px-5">Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td class="py-4 px-5">{{ \Carbon\Carbon::parse($detail->masa_kadaluwarsa)->format('d - m - Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button onclick="document.getElementById('modal-{{ $transaksi->id }}').classList.add('hidden')" class="bg-brandMaroon text-white px-8 py-2.5 rounded-lg text-sm font-medium hover:bg-red-800 transition-colors">
                    Kembali
                </button>
            </div>

        </div>
    </div>
@endforeach


<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            // Pindahkan modal ke lapisan paling luar (body) agar lepas dari batasan layout sidebar
            document.body.appendChild(modal);
            modal.classList.remove('hidden');
        }
    }

    // Menutup modal
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Tutup jika klik di luar box (overlay)
    window.onclick = function(event) {
        // Jika yang diklik adalah background hitam (overlay)
        if (event.target.classList.contains('fixed')) {
            event.target.classList.add('hidden');
        }
    }
</script>
@endsection