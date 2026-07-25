<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\ObatRusak;
use App\Models\StokBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObatRusakController extends Controller
{
    public function index()
    {
        $listRusak = ObatRusak::with(['obat', 'stokBatch', 'user'])
            ->orderBy('tanggal_lapor', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalBarangRusak = $listRusak->sum('jumlah');

        return view('obat-rusak.index', compact('listRusak', 'totalBarangRusak'));
    }

    public function create()
    {
        $obats = Obat::with('stokBatches')->get();
        return view('obat-rusak.create', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_id'       => 'required|exists:obats,id',
            'stok_batch_id' => 'nullable|exists:stok_batches,id',
            'jumlah'        => 'required|integer|min:1',
            'alasan'        => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
            'tanggal_lapor' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $obat = Obat::findOrFail($request->obat_id);

            if ($request->stok_batch_id) {
                $batch = StokBatch::findOrFail($request->stok_batch_id);
                $batch->stok = max(0, $batch->stok - $request->jumlah);
                $batch->save();
            }

            // Update total stok obat master berdasarkan akumulasi sisa batch
            if ($obat->stokBatches()->count() > 0) {
                $obat->stok = $obat->stokBatches()->sum('stok');
            } else {
                $obat->stok = max(0, $obat->stok - $request->jumlah);
            }
            $obat->save();

            // Simpan catatan obat rusak
            ObatRusak::create([
                'obat_id'       => $request->obat_id,
                'stok_batch_id' => $request->stok_batch_id,
                'user_id'       => auth()->id(),
                'jumlah'        => $request->jumlah,
                'alasan'        => $request->alasan,
                'keterangan'    => $request->keterangan,
                'tanggal_lapor' => $request->tanggal_lapor,
            ]);
        });

        return redirect()->route('obat-rusak.index')->with('success', 'Pencatatan obat rusak/retur berhasil disimpan dan stok otomatis disesuaikan.');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $item = ObatRusak::findOrFail($id);

            // Kembalikan stok jika catatan dibatalkan/dihapus
            if ($item->stok_batch_id) {
                $batch = StokBatch::find($item->stok_batch_id);
                if ($batch) {
                    $batch->stok += $item->jumlah;
                    $batch->save();
                }
            }

            $obat = Obat::find($item->obat_id);
            if ($obat) {
                if ($obat->stokBatches()->count() > 0) {
                    $obat->stok = $obat->stokBatches()->sum('stok');
                } else {
                    $obat->stok += $item->jumlah;
                }
                $obat->save();
            }

            $item->delete();
        });

        return redirect()->route('obat-rusak.index')->with('success', 'Catatan obat rusak berhasil dihapus dan stok dikembalikan.');
    }

    public function cetak(string $id)
    {
        $item = ObatRusak::with(['obat', 'stokBatch.pemasok', 'user'])->findOrFail($id);
        return view('obat-rusak.cetak', compact('item'));
    }
}
