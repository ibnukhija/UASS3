<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\DetailRestockItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Menampilkan halaman kasir
    public function index() {
        $items = Item::all();
        $kategori = Kategori::all();
        return view('pos.index', compact('items', 'kategori'));
    }

    // Memproses transaksi penjualan
    public function store(Request $request)
    {
        $keranjang = is_string($request->cart) ? json_decode($request->cart, true) : $request->cart;

        if (!$keranjang || !is_array($keranjang) || count($keranjang) == 0) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong!');
        }

        $transaksi = DB::transaction(function () use ($request, $keranjang) {
            // Simpan data utama transaksi
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'tanggal_transaksi' => now(),
                'total_harga' => $request->total_harga,
                'pembayaran' => $request->pembayaran,
                'kembalian' => $request->kembalian
            ]);

            foreach ($keranjang as $produk) {
                if ($produk['tipe'] == 'barang') {
                    // Simpan detail transaksi barang
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->transaksi_id,
                        'item_id' => $produk['item_id'],
                        'nama_service' => null,
                        'tipe' => 'barang',
                        'jumlah' => $produk['jumlah'],
                        'harga_jual_saat_itu' => $produk['harga_jual'],
                    ]);

                    // Potong total stok utama barang
                    $item = Item::find($produk['item_id']);
                    if ($item) {
                        $item->stok = $item->stok - $produk['jumlah'];
                        $item->save();
                    }

                    // Potong stok per batch menggunakan metode FIFO
                    $jumlahDibutuhkan = $produk['jumlah'];
                    $batches = DetailRestockItems::where('item_id', $produk['item_id'])
                        ->where('stok_tersisa', '>', 0)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    foreach ($batches as $batch) {
                        if ($jumlahDibutuhkan <= 0) break;

                        if ($batch->stok_tersisa >= $jumlahDibutuhkan) {
                            $batch->stok_tersisa -= $jumlahDibutuhkan;
                            $batch->save();
                            $jumlahDibutuhkan = 0;
                        } else {
                            $jumlahDibutuhkan -= $batch->stok_tersisa;
                            $batch->stok_tersisa = 0;
                            $batch->save();
                        }
                    }

                    // Update acuan harga beli ke batch aktif berikutnya
                    $nextOldestBatch = DetailRestockItems::where('item_id', $produk['item_id'])
                        ->where('stok_tersisa', '>', 0)
                        ->orderBy('created_at', 'asc')
                        ->first();

                    if ($nextOldestBatch && $item) {
                        $item->harga_beli = $nextOldestBatch->harga_beli_saat_itu;
                        $item->save();
                    }

                } else if ($produk['tipe'] == 'service') {
                    // Simpan detail transaksi jasa service
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->transaksi_id,
                        'item_id' => null,
                        'nama_service' => $produk['nama_service'],
                        'tipe' => 'service',
                        'jumlah' => 1, 
                        'harga_jual_saat_itu' => $produk['harga_service'], 
                    ]);
                }
            }

            return $transaksi;
        });

        return redirect()->back()->with([
            'success' => 'Transaksi berhasil disimpan!',
            'new_trx' => $transaksi->load('details.item')
        ]);
    }
}