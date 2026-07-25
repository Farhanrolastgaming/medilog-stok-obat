@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between gap-3 text-black mb-6 mt-2">
    <div class="flex items-center gap-3">
        <i class="fas fa-shopping-cart text-2xl"></i>
        <h1 class="text-2xl font-semibold">Pemesanan Stok </h1>
    </div>
    @if(auth()->user() && !in_array(strtolower(auth()->user()->role), ['admin', 'owner']))
    <a href="{{ route('pemesanan.create') }}" class="bg-[#F0FDF4] text-[#0d9488] border border-[#0d9488]/30 hover:bg-emerald-100 px-4 py-2 rounded-lg font-semibold flex items-center gap-2 transition duration-200 shadow-sm">
        <i class="fas fa-plus"></i> Tambah Pengajuan
    </a>
    @endif
</div>

@if ($message = Session::get('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
        <span class="text-sm text-gray-700 font-medium">Daftar Pengajuan Surat Pemesanan</span>
        <span class="text-xs text-gray-500 font-medium">Total: {{ $pemesanans->count() }} Pengajuan</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium w-16 text-center">No</th>
                        @if(auth()->user() && strtolower(auth()->user()->role) === 'admin')
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Pengaju</th>
                        @endif
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Pemasok Tujuan</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Obat & Merek</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">Jumlah</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Keterangan</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium text-center">Status</th>
                        <th class="py-3 px-4 font-medium text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pemesanans as $key => $pemesanan)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-4 border-r border-gray-200 text-center">{{ $key + 1 }}</td>
                        
                        @if(auth()->user() && strtolower(auth()->user()->role) === 'admin')
                        <td class="py-4 px-4 border-r border-gray-200">
                            <span class="font-medium text-gray-800 block">{{ $pemesanan->user->name ?? 'User' }}</span>
                            <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($pemesanan->created_at)->format('d/m/Y H:i') }}</span>
                        </td>
                        @endif

                        <td class="py-4 px-4 border-r border-gray-200 font-medium text-gray-800">
                            {{ $pemesanan->pemasok->nama_pemasok ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-4 border-r border-gray-200">
                            <span class="font-bold text-gray-800 block">{{ $pemesanan->nama_obat }}</span>
                            <span class="text-xs text-gray-500">{{ $pemesanan->merek ?? 'Generik' }}</span>
                        </td>
                        
                        <td class="py-4 px-4 border-r border-gray-200 text-center font-bold text-gray-800">
                            {{ $pemesanan->jumlah }}
                        </td>
                        
                        <td class="py-4 px-4 border-r border-gray-200 text-gray-600">
                            {{ $pemesanan->keterangan ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-4 border-r border-gray-200 text-center">
                            @php
                                $status = strtolower($pemesanan->status);
                                $bgClass = 'bg-gray-100 text-gray-800 border-gray-200'; // Default
                                
                                if($status === 'dalam proses' || $status === 'pending') {
                                    $bgClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                } elseif($status === 'diajukan') {
                                    $bgClass = 'bg-blue-100 text-blue-800 border-blue-200';
                                } elseif($status === 'selesai' || $status === 'approved') {
                                    $bgClass = 'bg-green-100 text-green-800 border-green-200';
                                } elseif($status === 'ditolak') {
                                    $bgClass = 'bg-red-100 text-red-800 border-red-200';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $bgClass }}">
                                {{ ucwords($pemesanan->status) }}
                            </span>
                        </td>

                        <td class="py-4 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                
                                @if(strtolower($pemesanan->status) !== 'ditolak')
                                <a href="{{ route('pemesanan.cetak', $pemesanan->id) }}" target="_blank" class="bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-200 text-xs font-bold py-1.5 px-3 rounded shadow-sm transition duration-200 flex items-center gap-1" title="Cetak Surat Pesanan">
                                    <i class="fas fa-print"></i> Cetak Surat
                                </a>
                                @endif

                                @if(auth()->user() && strtolower(auth()->user()->role) === 'admin')
                                <form action="{{ route('pemesanan.updateStatus', $pemesanan->id) }}" method="POST" class="flex items-center gap-1 m-0">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="text-xs border border-gray-300 rounded focus:ring-brandMaroon focus:border-brandMaroon py-1 px-1 bg-white" required>
                                        <option value="Dalam Proses" {{ strtolower($pemesanan->status) === 'dalam proses' ? 'selected' : '' }}>Dalam Proses</option>
                                        <option value="Diajukan" {{ strtolower($pemesanan->status) === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                        <option value="Ditolak" {{ strtolower($pemesanan->status) === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        <option value="Selesai" {{ strtolower($pemesanan->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    <button type="submit" class="bg-gray-800 hover:bg-black text-white text-xs font-bold py-1 px-2 rounded shadow-sm transition duration-200" title="Simpan Status">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ (auth()->user() && strtolower(auth()->user()->role) === 'admin') ? '8' : '7' }}" class="py-12 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum ada pengajuan pemesanan obat</p>
                            <p class="text-sm mt-1">Daftar obat yang diajukan ke pemasok akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection