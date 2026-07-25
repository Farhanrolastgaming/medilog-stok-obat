<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pesanan - {{ $pemesanan->nama_obat }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Pengaturan Kertas A4 & Cetak Tinta Hitam Pekat */
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
            <h1 class="text-4xl font-bold font-serif tracking-widest mt-2">SURAT</h1>
            <h1 class="text-4xl font-bold font-serif tracking-widest mt-2">PESANAN</h1>
        </div>
    </div>

    <div class="double-underline"></div>

    <div class="flex justify-between mb-6">
        <table class="w-1/2 border-none text-sm">
            <tr>
                <td class="border-none py-1 w-28">Nama Supplier</td>
                <td class="border-none py-1 w-2">:</td>
                <td class="border-none py-1 font-semibold">{{ $pemesanan->pemasok->nama_pemasok ?? '-' }}</td>
            </tr>
            <tr>
                <td class="border-none py-1">No. Telp</td>
                <td class="border-none py-1">:</td>
                <td class="border-none py-1">{{ $pemesanan->pemasok->telepon ?? '-' }}</td>
            </tr>
            <tr>
                <td class="border-none py-1 align-top">Alamat</td>
                <td class="border-none py-1 align-top">:</td>
                <td class="border-none py-1">{{ $pemesanan->pemasok->alamat ?? '-' }}</td>
            </tr>
        </table>

        <table class="w-[45%] border-none text-sm">
            <tr>
                <td class="border-none py-1 w-24">APJ</td>
                <td class="border-none py-1 w-2">:</td>
                <td class="border-none py-1 font-semibold">apt. {{ $pemesanan->user->name ?? 'Admin' }}, S.Farm</td>
            </tr>
            <tr>
                <td class="border-none py-1">Tanggal</td>
                <td class="border-none py-1">:</td>
                <td class="border-none py-1">{{ \Carbon\Carbon::parse($pemesanan->created_at)->format('d M Y H:i:s') }}</td>
            </tr>
            <tr>
                <td class="border-none py-1">No. PO</td>
                <td class="border-none py-1">:</td>
                <td class="border-none py-1 font-semibold">PO-{{ \Carbon\Carbon::parse($pemesanan->created_at)->format('Ym') }}{{ str_pad($pemesanan->id, 4, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="border-none py-1">Jenis</td>
                <td class="border-none py-1">:</td>
                <td class="border-none py-1">REGULER</td>
            </tr>
        </table>
    </div>

    <table class="mb-4">
        <thead>
            <tr>
                <th class="w-12">No</th>
                <th>Nama Obat & Merek</th>
                <th class="w-48">Keterangan</th>
                <th class="w-24">Qty</th>
                <th class="w-32">Satuan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>
                    <strong>{{ $pemesanan->nama_obat }}</strong>
                    @if($pemesanan->merek)
                    <br><span class="text-xs">{{ $pemesanan->merek }}</span>
                    @endif
                </td>
                <td>{{ $pemesanan->keterangan ?? '-' }}</td>
                <td class="text-center font-bold">{{ $pemesanan->jumlah }}</td>
                <td class="text-center">Box / Pcs</td>
            </tr>
        </tbody>
    </table>

    <div class="mb-12">
        <span class="font-semibold">Catatan :</span> Harap segera diproses dan dikirimkan sesuai dengan alamat yang tertera.
    </div>

    <div class="flex justify-between items-end mt-16 text-center">
        <div class="w-64">
            <p class="mb-20">Supplier</p>
            <div class="border-b border-black w-full"></div>
        </div>

        <div class="w-64">
            <p class="mb-20">Apotek Medilog</p>
            <p class="font-bold border-b border-black w-full pb-1">apt. {{ $pemesanan->user->name ?? 'Admin' }}, S.Farm</p>
            <p class="mt-1 text-xs">No. SIPA: SIPA/123456/XII/2026</p>
        </div>
    </div>

    <div class="mt-10 text-center no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 mr-2">Cetak Ulang</button>
        <a href="{{ route('pemesanan.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">Kembali</a>
    </div>

</body>
</html>