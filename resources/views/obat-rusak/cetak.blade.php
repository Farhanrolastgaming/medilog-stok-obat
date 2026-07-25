<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengantar Retur - {{ $item->obat->nama_obat ?? 'Obat' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        body { font-family: 'Arial', sans-serif; color: #000; -webkit-print-color-adjust: exact; }
        .double-underline { border-bottom: 3px double #000; margin-top: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa !important; text-align: center; font-weight: bold; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-white text-black text-sm" onload="window.print()">

    <!-- Header Kop Apotek -->
    <div class="flex justify-between items-start">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gray-200 border-2 border-black flex items-center justify-center font-bold text-xl">
                ML
            </div>
            <div>
                <h1 class="text-xl font-bold">Apotek Medilog</h1>
                <p class="font-semibold">No. Surat Izin Apotek : SIA/123456/2026</p>
                <p>Jl. Merdeka No.45, Karanganyar, Jawa Tengah</p>
                <p>Telp. 0812-3456-7890, Email : admin@medilog.com</p>
            </div>
        </div>
        <div class="text-right">
            <h1 class="text-3xl font-bold font-serif tracking-widest mt-2">SURAT PENGANTAR</h1>
            <h1 class="text-3xl font-bold font-serif tracking-widest text-red-700">RETUR OBAT RUSAK</h1>
        </div>
    </div>

    <div class="double-underline"></div>

    <!-- Info Transaksi Retur & Supplier -->
    <div class="flex justify-between mb-6">
        <table class="w-1/2 border-none text-sm">
            <tr>
                <td class="border-none py-1 w-32">Pemasok / PBF</td>
                <td class="border-none py-1 w-2">:</td>
                <td class="border-none py-1 font-semibold">{{ $item->stokBatch->pemasok->nama_pemasok ?? 'Distributor Utama' }}</td>
            </tr>
            <tr>
                <td class="border-none py-1">No. Telp / Kontak</td>
                <td class="border-none py-1">:</td>
                <td class="border-none py-1">{{ $item->stokBatch->pemasok->telepon ?? '-' }}</td>
            </tr>
            <tr>
                <td class="border-none py-1 align-top">Alamat Pemasok</td>
                <td class="border-none py-1 align-top">:</td>
                <td class="border-none py-1">{{ $item->stokBatch->pemasok->alamat ?? '-' }}</td>
            </tr>
        </table>

        <table class="w-[45%] border-none text-sm">
            <tr>
                <td class="border-none py-1 w-32">No. Surat Retur</td>
                <td class="border-none py-1 w-2">:</td>
                <td class="border-none py-1 font-semibold">RTR-{{ \Carbon\Carbon::parse($item->tanggal_lapor)->format('Ym') }}{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="border-none py-1">Tanggal Lapor</td>
                <td class="border-none py-1">:</td>
                <td class="border-none py-1">{{ \Carbon\Carbon::parse($item->tanggal_lapor)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="border-none py-1">Petugas / APJ</td>
                <td class="border-none py-1">:</td>
                <td class="border-none py-1 font-semibold">apt. {{ $item->user->name ?? 'Admin' }}, S.Farm</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Rincian Obat Rusak -->
    <table class="mb-4">
        <thead>
            <tr>
                <th class="w-12">No</th>
                <th>Kode & Nama Obat</th>
                <th class="w-40">Merek / Batch</th>
                <th class="w-24">Jumlah</th>
                <th class="w-48">Penyebab / Alasan Kerusakan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>
                    <strong>{{ $item->obat->nama_obat ?? 'Obat' }}</strong>
                    <br><span class="text-xs text-gray-600">Kode: [{{ $item->obat->kode_obat ?? '-' }}]</span>
                </td>
                <td>
                    <strong>{{ $item->stokBatch->merek ?? 'Generik' }}</strong>
                    @if($item->stokBatch && $item->stokBatch->expired_date)
                        <br><span class="text-xs">Exp: {{ \Carbon\Carbon::parse($item->stokBatch->expired_date)->format('d-m-Y') }}</span>
                    @endif
                </td>
                <td class="text-center font-bold text-base">{{ $item->jumlah }} {{ $item->obat->satuan ?? 'pcs' }}</td>
                <td><strong>{{ $item->alasan }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="mb-8 p-3 border border-black bg-gray-50 rounded">
        <span class="font-bold">Catatan / Keterangan Tambahan :</span>
        <p class="mt-1">{{ $item->keterangan ?? 'Mohon segera diproses penggantian barang utuh / penyesuaian nota kredit (Credit Note) sesuai regulasi persediaan.' }}</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="flex justify-between items-end mt-16 text-center">
        <div class="w-64">
            <p class="mb-20">Penerima Retur (PBF / Distributor)</p>
            <p class="font-bold border-b border-black w-full pb-1">( .................................................... )</p>
            <p class="mt-1 text-xs">Cap & Tanda Tangan Distributor</p>
        </div>

        <div class="w-64">
            <p class="mb-20">Pengirim (Apotek Medilog)</p>
            <p class="font-bold border-b border-black w-full pb-1">apt. {{ $item->user->name ?? 'Admin' }}, S.Farm</p>
            <p class="mt-1 text-xs">No. SIPA: SIPA/123456/XII/2026</p>
        </div>
    </div>

    <!-- Tombol Aksi Cetak Ulang -->
    <div class="mt-10 text-center no-print">
        <button onclick="window.print()" class="bg-teal-700 text-white px-5 py-2 rounded shadow hover:bg-teal-800 mr-2 font-semibold">
            <i class="fas fa-print"></i> Cetak Ulang Surat Retur
        </button>
        <a href="{{ route('obat-rusak.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded shadow hover:bg-gray-600 font-semibold">
            Kembali
        </a>
    </div>

</body>
</html>
