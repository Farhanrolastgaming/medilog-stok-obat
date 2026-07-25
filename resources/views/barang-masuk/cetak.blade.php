<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barang Masuk {{ $transaksi->kode_transaksi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 1cm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: white !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white text-black p-8 max-w-4xl mx-auto font-sans" onload="window.print()">

    <div class="border-b-2 border-gray-800 pb-4 mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-wider">MEDILOG</h1>
            <p class="text-sm text-gray-600 mt-1">Sistem Informasi Manajemen Apotek</p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-gray-800 uppercase">Bukti Barang Masuk</h2>
            <p class="text-sm text-gray-600">{{ $transaksi->kode_transaksi }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-y-4 gap-x-8 mb-8">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Masuk</p>
            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Pemasok</p>
            <p class="font-medium text-gray-900">{{ $transaksi->Pemasok->nama_pemasok ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Diterima Oleh (Admin)</p>
            <p class="font-medium text-gray-900">{{ $transaksi->User->name ?? 'Admin' }}</p>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Kuantitas</p>
            <p class="font-medium text-gray-900">{{ abs($transaksi->DetailTransaksi->sum('jumlah_masuk')) }} pcs</p>
        </div>
    </div>

    <table class="w-full text-sm text-left mb-8">
        <thead class="bg-gray-100 border-y border-gray-800 text-gray-900">
            <tr>
                <th class="py-3 px-2 font-bold w-12 text-center">No</th>
                <th class="py-3 px-2 font-bold">Obat & Merek</th>
                <th class="py-3 px-2 font-bold text-center">Jumlah</th>
                <th class="py-3 px-2 font-bold text-right">Harga Beli</th>
                <th class="py-3 px-2 font-bold text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->DetailTransaksi as $index => $detail)
            <tr class="border-b border-gray-200">
                <td class="py-3 px-2 text-center">{{ $index + 1 }}</td>
                <td class="py-3 px-2">
                    <span class="font-bold text-gray-900 block">{{ $detail->obat->nama_obat ?? 'Dihapus' }}</span>
                    <span class="text-gray-500 text-xs">{{ $detail->merek ?? 'Generik' }}</span>
                </td>
                <td class="py-3 px-2 text-center">{{ abs($detail->jumlah_masuk) }}</td>
                <td class="py-3 px-2 text-right">Rp. {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                <td class="py-3 px-2 text-right font-medium">Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="border-b-2 border-gray-800">
                <td colspan="4" class="py-4 px-2 text-right font-bold text-gray-800 uppercase tracking-widest">Total Tagihan</td>
                <td class="py-4 px-2 text-right font-bold text-xl text-gray-900">Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="flex justify-between mt-12 text-center px-12">
        <div>
            <p class="text-sm text-gray-600 mb-16">Pemasok / Kurir,</p>
            <p class="font-bold text-gray-900 border-b border-gray-400 pb-1 inline-block px-4">
                {{ $transaksi->Pemasok->nama_pemasok ?? '( ........................ )' }}
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-16">Penerima,</p>
            <p class="font-bold text-gray-900 border-b border-gray-400 pb-1 inline-block px-4">
                {{ $transaksi->User->name ?? 'Admin' }}
            </p>
        </div>
    </div>

    <div class="mt-8 text-center no-print">
        <button onclick="window.close()" class="bg-gray-500 text-white px-6 py-2 rounded shadow hover:bg-gray-600">Tutup Halaman</button>
    </div>

</body>
</html>