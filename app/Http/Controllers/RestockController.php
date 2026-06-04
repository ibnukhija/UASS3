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

    // Menyimpan data restock baru
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
            // Simpan data utama restock
            $restock = RestockItem::create([
                'tanggal_masuk' => $request->tanggal_masuk,
                'nama_toko' => $request->nama_toko,
                'keterangan' => $request->keterangan ?? '-'
            ]);

            // Simpan detail item dan update stok
            foreach($request->items as $itemData) {
                DetailRestockItems::create([
                    'restock_id' => $restock->restock_id,
                    'item_id' => $itemData['item_id'],
                    'jumlah' => $itemData['jumlah'],
                    'stok_tersisa' => $itemData['jumlah'], 
                    'harga_beli_saat_itu' => $itemData['harga_beli']
                ]);

                // Update total stok barang
                Item::where('item_id', $itemData['item_id'])
                    ->increment('stok', $itemData['jumlah']);
                
                // Atur acuan harga beli dari batch terlama yang masih ada stok
                $oldestBatch = DetailRestockItems::where('item_id', $itemData['item_id'])
                    ->where('stok_tersisa', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->first();

                if ($oldestBatch) {
                    Item::where('item_id', $itemData['item_id'])
                        ->update(['harga_beli' => $oldestBatch->harga_beli_saat_itu]);
                }
            }
        });

        return redirect()->route('items.index')->with('success', 'Stok berhasil ditambahkan!');
    }
    
    // Menghapus data restock
    public function destroy($id) {
        $restock = RestockItem::with('details')->findOrFail($id);
        
        DB::transaction(function() use ($restock) {
            foreach($restock->details as $detail) {
                Item::where('item_id', $detail->item_id)
                    ->decrement('stok', $detail->jumlah);
            }
            $restock->delete(); 
        });

        return back()->with('success', 'Data restock dihapus dan stok dikembalikan.');
    }
}