<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    //Menampilkan daftar barang
    public function index(Request $request) {
        $query = Item::query();
        
        // Fitur Pencarian
        if($request->search) {
            $query->where('nama_item', 'like', '%'.$request->search.'%')
                ->orWhere('kategori', 'like', '%'.$request->search.'%');
        }
        $items = $query->paginate(10);
        
        return view('items.index', compact('items'));
    }

    //Menampilkan form tambah barang
    public function create() {
        return view('items.create');
    }

    //Menyimpan barang baru ke database
    public function store(Request $request) {
        $data = $request->validate([
            'nama_item' => 'required',
            'kategori' => 'required',
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

        Item::create($data);
        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan');
    }

    //Menampilkan form edit barang
    public function edit($id) {
        $item = Item::findOrFail($id);
        return view('items.edit', compact('item'));
    }

    //Menyimpan perubahan data barang
    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);
        
        $data = $request->validate([
            'nama_item' => 'required',
            'kategori' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'foto' => 'nullable|image|max:2048'
        ]);

        // Cek jika ada upload foto baru
        if($request->hasFile('foto')) {
            if($item->foto && $item->foto != 'default.png') {
                Storage::disk('public')->delete($item->foto);
            }
            $data['foto'] = $request->file('foto')->store('items', 'public');
        }

        $item->update($data);
        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui');
    }

    //Menghapus barang
    public function destroy($id) {
        $item = Item::findOrFail($id);

        if($item->foto && $item->foto != 'default.png') {
            Storage::disk('public')->delete($item->foto);
        }
        
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus');
    }
}