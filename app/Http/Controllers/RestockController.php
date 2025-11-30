<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\RestockItem;
use App\Models\DetailRestockItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    // Menampilkan riwayat restock
    public function index() {
        $restocks = RestockItem::orderBy('tanggal_masuk', 'desc')->paginate(10);
        return view('restock.index', compact('restocks'));
    }

    // Form tambah stok
    public function create() {
        $items = Item::all(); 
        return view('restock.create', compact('items'));
    }

    // Proses Simpan Restock
    public function store(Request $request) {
        $request->validate([
            'nama_toko' => 'required',
            'tanggal_masuk' => 'required|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.harga_beli' => 'required|numeric',
        ]);

        DB::transaction(function() use ($request) {
            //Simpan Header Restock
            $restock = RestockItem::create([
                'tanggal_masuk' => $request->tanggal_masuk,
                'nama_toko' => $request->nama_toko,
                'keterangan' => $request->keterangan ?? '-'
            ]);

            //Simpan Detail & Update Stok Barang
            foreach($request->items as $itemData) {
                DetailRestockItems::create([
                    'restock_id' => $restock->restock_id,
                    'item_id' => $itemData['item_id'],
                    'jumlah' => $itemData['jumlah'],
                    'harga_beli_saat_itu' => $itemData['harga_beli']
                ]);

                Item::where('item_id', $itemData['item_id'])
                    ->increment('stok', $itemData['jumlah']);
                
                // Item::where('item_id', $itemData['item_id'])
                //    ->update(['harga_beli' => $itemData['harga_beli']]);
            }
        });

        return redirect()->route('items.index')->with('success', 'Stok berhasil ditambahkan!');
    }
    
    public function destroy($id) {
        $restock = RestockItem::with('details')->findOrFail($id);
        
        foreach($restock->details as $detail) {
            Item::where('item_id', $detail->item_id)
                ->decrement('stok', $detail->jumlah);
        }

        $restock->delete(); 
        return back()->with('success', 'Data restock dihapus dan stok dikembalikan.');
    }
}