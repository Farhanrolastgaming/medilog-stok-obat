<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Stok</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@media print { @page { margin: 1cm; } body { -webkit-print-color-adjust: exact; } .no-print { display: none; } }</style>
</head>
<body class="bg-white text-black p-8 font-sans text-sm" onload="window.print()">
    <div class="text-center mb-6 border-b-2 border-black pb-4">
        <h1 class="text-2xl font-bold uppercase tracking-wider">MEDILOG - Laporan Stok</h1>
        <p class="text-gray-600">
            Periode: {{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : 'Awal' }} 
            s.d 
            {{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : 'Sekarang' }}
        </p>
    </div>

    <table class="w-full text-left border-collapse border border-gray-400 mb-8">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-400 py-2 px-3">Tanggal</th>
                <th class="border border-gray-400 py-2 px-3 text-center">Tipe</th>
                <th class="border border-gray-400 py-2 px-3">No. Trx</th>
                <th class="border border-gray-400 py-2 px-3">Ket / Pemasok</th>
                <th class="border border-gray-400 py-2 px-3">Obat & Merek</th>
                <th class="border border-gray-400 py-2 px-3 text-center">Mutasi</th>
                <th class="border border-gray-400 py-2 px-3 text-right">Hrg Satuan</th>
                <th class="border border-gray-400 py-2 px-3 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mutasiStok as $mutasi)
            @php
                $isMasuk = $mutasi->jumlah_masuk > 0;
                $keterangan = $isMasuk ? ($mutasi->transaksi->Pemasok->nama_pemasok ?? 'Pemasok Dihapus') : 'Penjualan Luring';
                $jumlah = abs($mutasi->jumlah_masuk);
                $hargaSatuan = $isMasuk ? $mutasi->harga_beli : ($jumlah > 0 ? $mutasi->subtotal / $jumlah : 0);
            @endphp
            <tr>
                <td class="border border-gray-400 py-2 px-3">{{ \Carbon\Carbon::parse($mutasi->transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                <td class="border border-gray-400 py-2 px-3 text-center font-bold {{ $isMasuk ? 'text-green-600' : 'text-red-600' }}">{{ $isMasuk ? 'Masuk' : 'Keluar' }}</td>
                <td class="border border-gray-400 py-2 px-3">{{ $mutasi->transaksi->kode_transaksi }}</td>
                <td class="border border-gray-400 py-2 px-3">{{ $keterangan }}</td>
                <td class="border border-gray-400 py-2 px-3 font-bold">{{ $mutasi->obat->nama_obat ?? '-' }} <span class="font-normal text-xs block">{{ $mutasi->merek ?? 'Generik' }}</span></td>
                <td class="border border-gray-400 py-2 px-3 text-center">{{ $isMasuk ? '+' : '-' }}{{ $jumlah }}</td>
                <td class="border border-gray-400 py-2 px-3 text-right">Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                <td class="border border-gray-400 py-2 px-3 text-right">Rp {{ number_format($mutasi->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>