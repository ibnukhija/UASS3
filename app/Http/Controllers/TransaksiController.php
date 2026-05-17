<?php
namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index() {
        $items = Item::all();
        $kategori = Kategori::all();
        return view('pos.index', compact('items', 'kategori'));
    }

    public function store(Request $request)
    {
        // 1. Tangkap data cart dan ubah jadi Array
        $keranjang = is_string($request->cart) ? json_decode($request->cart, true) : $request->cart;

        // 2. Proteksi (Cegah Error jika kasir iseng klik bayar saat keranjang kosong)
        if (!$keranjang || !is_array($keranjang) || count($keranjang) == 0) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong!');
        }

        // 3. Simpan Header Transaksi
        $transaksi = Transaksi::create([
            'user_id' => Auth::id(),
            'tanggal_transaksi' => now(),
            'total_harga' => $request->total_harga,
            'pembayaran' => $request->pembayaran,
            'kembalian' => $request->kembalian
        ]);

        foreach ($keranjang as $produk) {
            if ($produk['tipe'] == 'barang') {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->transaksi_id, // Menggunakan transaksi_id
                    'item_id' => $produk['item_id'],
                    'nama_service' => null,
                    'tipe' => 'barang',
                    'jumlah' => $produk['jumlah'],
                    'harga_jual_saat_itu' => $produk['harga_jual'],
                ]);

                // Potong stok otomatis
                $item = Item::find($produk['item_id']);
                if ($item) {
                    $item->stok = $item->stok - $produk['jumlah'];
                    $item->save();
                }

            } else if ($produk['tipe'] == 'service') {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->transaksi_id, // Menggunakan transaksi_id
                    'item_id' => null,
                    'nama_service' => $produk['nama_service'],
                    'tipe' => 'service',
                    'jumlah' => 1, 
                    'harga_jual_saat_itu' => $produk['harga_service'], 
                ]);
            }
        }

        return redirect()->back()->with([
            'success' => 'Transaksi berhasil disimpan!',
            'new_trx' => $transaksi->load('details.item') 
        ]);
    }
}