<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Barang Keluar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@media print { @page { margin: 1cm; } body { -webkit-print-color-adjust: exact; } .no-print { display: none; } }</style>
</head>
<body class="bg-white text-black p-8 font-sans text-sm" onload="window.print()">
    <div class="text-center mb-6 border-b-2 border-black pb-4">
        <h1 class="text-2xl font-bold uppercase tracking-wider">MEDILOG - Laporan Penjualan (Keluar)</h1>
        <p class="text-gray-600">
            Periode: {{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : 'Awal' }} 
            s.d 
            {{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : 'Sekarang' }}
        </p>
    </div>

    <table class="w-full text-left border-collapse border border-gray-400 mb-8">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-400 py-2 px-3 text-center">No</th>
                <th class="border border-gray-400 py-2 px-3">Tanggal</th>
                <th class="border border-gray-400 py-2 px-3">Kasir</th>
                <th class="border border-gray-400 py-2 px-3">Obat & Merek</th>
                <th class="border border-gray-400 py-2 px-3 text-center">Jml</th>
                <th class="border border-gray-400 py-2 px-3 text-right">Harga Satuan</th>
                <th class="border border-gray-400 py-2 px-3 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($transaksis as $transaksi)
                @foreach ($transaksi->DetailTransaksi as $detail)
                <tr>
                    @if ($loop->first)
                    <td rowspan="{{ $transaksi->DetailTransaksi->count() }}" class="border border-gray-400 py-2 px-3 text-center align-top">{{ $no++ }}</td>
                    <td rowspan="{{ $transaksi->DetailTransaksi->count() }}" class="border border-gray-400 py-2 px-3 align-top">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                    <td rowspan="{{ $transaksi->DetailTransaksi->count() }}" class="border border-gray-400 py-2 px-3 align-top">{{ $transaksi->User->name ?? 'Admin' }}</td>
                    @endif
                    <td class="border border-gray-400 py-2 px-3 font-bold">{{ $detail->Obat->nama_obat ?? '-' }} <span class="font-normal text-xs block">{{ $detail->merek ?? 'Generik' }}</span></td>
                    <td class="border border-gray-400 py-2 px-3 text-center">{{ abs($detail->jumlah_masuk) }}</td>
                    <td class="border border-gray-400 py-2 px-3 text-right">Rp {{ number_format($detail->subtotal / max(abs($detail->jumlah_masuk), 1), 0, ',', '.') }}</td>
                    <td class="border border-gray-400 py-2 px-3 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray-100 font-bold">
                <td colspan="6" class="border border-gray-400 py-3 px-3 text-right uppercase">Total Penjualan:</td>
                <td class="border border-gray-400 py-3 px-3 text-right text-lg">Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>