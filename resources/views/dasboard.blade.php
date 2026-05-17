
@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-home text-2xl"></i>
    <h1 class="text-2xl font-semibold">Dashboard</h1>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex flex-col md:flex-row items-center justify-between border border-gray-100">
    <div class="flex items-center gap-4 flex-1 w-full justify-center md:justify-start">
        <i class="fas fa-boxes text-4xl text-gray-700"></i>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Obat</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalObat }}</div>
        </div>
    </div>
    <div class="hidden md:block w-px h-12 bg-gray-200 mx-6"></div>

    <div class="flex items-center gap-4 flex-1 w-full justify-center md:justify-start mt-4 md:mt-0">
        <i class="fas fa-inbox text-4xl text-brandGreen"></i>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Obat Masuk</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalObatMasuk }}</div>
        </div>
    </div>
    <div class="hidden md:block w-px h-12 bg-gray-200 mx-6"></div>

    <div class="flex items-center gap-4 flex-1 w-full justify-center md:justify-start mt-4 md:mt-0">
        <i class="fas fa-dolly text-4xl text-gray-700"></i>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Obat Keluar</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalObatKeluar }}</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-5 border border-gray-100">
        <div class="w-16 h-16 rounded-xl bg-brandGreen flex items-center justify-center text-white shadow-sm">
            <i class="fas fa-file-alt text-2xl"></i>
        </div>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Jenis Obat</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalJenisObat }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-5 flex items-center gap-5 border border-gray-100">
        <div class="w-16 h-16 rounded-xl bg-gray-700 flex items-center justify-center text-white shadow-sm">
            <i class="fas fa-user text-2xl"></i>
        </div>
        <div>
            <div class="text-sm text-gray-500 font-medium">Data Pengguna</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalPengguna }}</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center gap-2 bg-gray-50 rounded-t-lg">
        <i class="fas fa-info-circle text-gray-600"></i>
        <span class="text-sm text-gray-700 font-medium">Stok obat yang perlu diisi/dikeluarkan</span>
    </div>

    <div class="p-5">
        <div class="flex items-center gap-2 mb-4 text-sm text-gray-600">
            Tampilkan
            <select class="border border-gray-300 rounded px-3 py-1 outline-none focus:border-brandGreen focus:ring-1 focus:ring-brandGreen bg-white">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
            data
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-center border border-gray-200">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium w-16">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Kode Obat</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Nama Obat</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Jenis Obat</th>
                        <th class="py-3 px-4 font-medium">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @if($obatNeedAttention->isEmpty())
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td colspan="5" class="py-6 text-center text-gray-500">Tidak ada data obat dengan stok rendah</td>
                    </tr>
                    @else
                    @foreach($obatNeedAttention as $obat)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 border-r border-gray-200">{{ $loop->iteration }}</td>
                        <td class="py-3 border-r border-gray-200">{{ $obat->id }}</td>
                        <td class="py-3 border-r border-gray-200">{{ $obat->nama_obat }}</td>
                        <td class="py-3 border-r border-gray-200">{{ $obat->jenis_obat }}</td>
                        <td class="py-3 {{ ($obat->stok ?? 0) < 10 ? 'text-red-600 font-semibold' : '' }}">{{ $obat->stok ?? 0 }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
