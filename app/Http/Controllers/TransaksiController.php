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

    public function store(Request $request) {
        $cart = json_decode($request->cart, true);
        
        $trx = null;

        DB::transaction(function() use ($request, $cart, &$trx) {
            $trx = Transaksi::create([
                'user_id' => Auth::id(),
                'tanggal_transaksi' => now(),
                'total_harga' => $request->total_harga,
                'pembayaran' => $request->pembayaran,
                'kembalian' => $request->kembalian
            ]);

            foreach($cart as $c) {
                DetailTransaksi::create([
                    'transaksi_id' => $trx->transaksi_id, 
                    'item_id' => $c['id'], 
                    'jumlah' => $c['qty'],
                    'harga_jual_saat_itu' => $c['price']
                ]);
                
                //untuk mengurangi stok
                Item::where('item_id', $c['id'])->decrement('stok', $c['qty']);
            }
        });
        
        if ($trx) {
            $trx->load(['details.item', 'user']);

            return redirect()->back()->with('success', 'Transaksi Berhasil!')->with('new_trx', $trx);
        }

        return redirect()->back()->with('error', 'Transaksi Gagal!');
    }
}