@extends('layouts.app')

@section('content')
<div class="flex items-center gap-3 text-black mb-6 mt-2">
    <i class="fas fa-user-edit text-2xl"></i>
    <h1 class="text-2xl font-semibold">Edit Data Pemasok</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Sidebar Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 sticky top-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-lg bg-brandGreen flex items-center justify-center text-white">
                    <i class="fas fa-info-circle text-lg"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Petunjuk Edit</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">
                Perbarui data distributor obat jika terdapat perubahan kontak sales, alamat kantor, atau nomor rekening bank.
            </p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
            <form action="{{ route('pemasok.update', $pemasok->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Pemasok -->
                <div>
                    <label for="nama_pemasok" class="block text-sm font-semibold text-gray-800 mb-2">
                        Nama Pemasok / Distributor <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_pemasok"
                        id="nama_pemasok"
                        placeholder="Contoh: PT Kalbe Farma Tbk"
                        class="w-full px-4 py-2.5 border @error('nama_pemasok') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen transition text-sm"
                        value="{{ old('nama_pemasok', $pemasok->nama_pemasok) }}"
                        required
                    />
                    @error('nama_pemasok')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Sales/PIC -->
                    <div>
                        <label for="nama_pic" class="block text-sm font-semibold text-gray-800 mb-2">
                            Nama Sales / PIC
                        </label>
                        <input
                            type="text"
                            name="nama_pic"
                            id="nama_pic"
                            placeholder="Contoh: Bpk. Budi"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen transition text-sm"
                            value="{{ old('nama_pic', $pemasok->nama_pic) }}"
                        />
                    </div>

                    <!-- No. Telepon/WA -->
                    <div>
                        <label for="telepon" class="block text-sm font-semibold text-gray-800 mb-2">
                            No. Telepon / WhatsApp
                        </label>
                        <input
                            type="text"
                            name="telepon"
                            id="telepon"
                            placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen transition text-sm"
                            value="{{ old('telepon', $pemasok->telepon) }}"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Email Pemasok -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">
                            Email Pemasok
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Contoh: sales@kalbe.co.id"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen transition text-sm"
                            value="{{ old('email', $pemasok->email) }}"
                        />
                    </div>

                    <!-- Kota -->
                    <div>
                        <label for="kota" class="block text-sm font-semibold text-gray-800 mb-2">
                            Kota Asal
                        </label>
                        <input
                            type="text"
                            name="kota"
                            id="kota"
                            placeholder="Contoh: Jakarta Selatan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen transition text-sm"
                            value="{{ old('kota', $pemasok->kota) }}"
                        />
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-gray-800 mb-2">
                        Alamat Lengkap Kantor/Gudang
                    </label>
                    <textarea
                        name="alamat"
                        id="alamat"
                        rows="2"
                        placeholder="Contoh: Jl. Industri No. 45, Kawasan Industri Pulogadung"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen transition text-sm"
                    >{{ old('alamat', $pemasok->alamat) }}</textarea>
                </div>

                <!-- No. Rekening Bank -->
                <div>
                    <label for="no_rekening" class="block text-sm font-semibold text-gray-800 mb-2">
                        No. Rekening Bank Pembayaran
                    </label>
                    <input
                        type="text"
                        name="no_rekening"
                        id="no_rekening"
                        placeholder="Contoh: BCA 1234567890 a.n PT Kalbe Farma"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandGreen transition text-sm"
                        value="{{ old('no_rekening', $pemasok->no_rekening) }}"
                    />
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
                        class="flex-1 px-6 py-3 bg-brandMaroon rounded-lg font-medium text-white hover:bg-teal-800 transition flex items-center justify-center gap-2 shadow-sm"
                    >
                        <i class="fas fa-save"></i>
                        Perbarui Pemasok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
