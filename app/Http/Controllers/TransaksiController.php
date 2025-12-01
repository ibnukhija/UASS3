<?php
namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index() {
        $items = Item::all();

        $categories = [
            'Oli & Pelumas', 'Sistem Rem', 'Kelistrikan', 'Ban & Velg', 
            'Filter udara', 'Transmisi', 'Body & Aksesoris', 'Suspensi', 'Kabel & Selang'
        ];
        return view('pos.index', compact('items', 'categories'));
    }

    public function store(Request $request) {
        $cart = json_decode($request->cart, true);
        
        DB::transaction(function() use ($request, $cart) {
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
                
                Item::where('item_id', $c['id'])->decrement('stok', $c['qty']);
            }
        });

        return redirect()->back()->with('success', 'Transaksi Berhasil!');
    }
}