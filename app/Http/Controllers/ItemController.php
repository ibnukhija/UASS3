<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Kategori;
use App\Models\RestockItem;
use App\Models\DetailRestockItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    // Menampilkan daftar barang
    public function index(Request $request) {
        $query = Item::query();
        
        if($request->search) {
            $query->where('nama_item', 'like', '%'.$request->search.'%')
                ->orWhere('kategori', 'like', '%'.$request->search.'%');
        }
        $items = $query->paginate(10);
        return view('items.index', compact('items'));
    }

    // Menampilkan form tambah barang
    public function create() {
        $kategori = Kategori::all();
        return view('items.create', compact('kategori'));
    }

    // Menyimpan barang baru dan inisialisasi batch awal
    public function store(Request $request) {
        $data = $request->validate([
            'nama_item' => 'required',
            'kategori_id' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'foto' => 'nullable|image|max:2048'
        ]);
        
        if($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('items', 'public');
        } else {
            $data['foto'] = 'default.png';
        }

        DB::transaction(function() use ($data) {
            $item = Item::create($data);

            // Buat data batch awal otomatis jika stok langsung diisi
            if ($item->stok > 0) {
                $initialRestock = RestockItem::create([
                    'tanggal_masuk' => now(),
                    'nama_toko' => 'Saldo Stok Bawaan',
                    'keterangan' => 'Otomatis dari input produk baru'
                ]);

                DetailRestockItems::create([
                    'restock_id' => $initialRestock->restock_id,
                    'item_id' => $item->item_id,
                    'jumlah' => $item->stok,
                    'stok_tersisa' => $item->stok,
                    'harga_beli_saat_itu' => $item->harga_beli
                ]);
            }
        });

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan');
    }

    // Menampilkan form edit barang
    public function edit($id) {
        $item = Item::findOrFail($id);
        $kategori = Kategori::all();
        return view('items.edit', compact('item', 'kategori'));
    }

    // Menyimpan perubahan data barang dan sinkronisasi harga batch terlama
    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);
        
        $data = $request->validate([
            'nama_item' => 'required',
            'kategori_id' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'foto' => 'nullable|image|max:2048'
        ]);

        if($request->hasFile('foto')) {
            if($item->foto && $item->foto != 'default.png') {
                Storage::disk('public')->delete($item->foto);
            }
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        DB::transaction(function() use ($item, $data, $request) {
            $item->update($data);

            // Update harga beli pada batch aktif terlama
            $oldestBatch = DetailRestockItems::where('item_id', $item->item_id)
                ->where('stok_tersisa', '>', 0)
                ->orderBy('created_at', 'asc')
                ->first();

            if ($oldestBatch) {
                $oldestBatch->update(['harga_beli_saat_itu' => $request->harga_beli]);
            }
        });

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui');
    }

    // Menghapus barang
    public function destroy($id) {
        $item = Item::findOrFail($id);

        if($item->foto && $item->foto != 'default.png') {
            Storage::disk('public')->delete($item->foto);
        }
        
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus');
    }
}