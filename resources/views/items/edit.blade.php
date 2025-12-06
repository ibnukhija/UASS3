@extends('layout.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg border-t-4 border-racing-orange">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold uppercase text-gray-800"><i class="fa-solid fa-edit"></i> Edit Sparepart</h2>
        <a href="{{ route('items.index') }}" class="text-gray-500 hover:text-racing-orange">Kembali</a>
    </div>

    <form action="{{ route('items.update', $item->item_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="col-span-2">
                <label class="block font-bold mb-1">Nama Item</label>
                <input type="text" name="nama_item" value="{{ $item->nama_item }}" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required>
            </div>

            <div>
                <label class="block font-bold mb-1">Kategori</label>
                <select name="kategori" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none">
                    @foreach(['Oli & Pelumas', 'Sistem Rem', 'Kelistrikan', 'Ban & Velg', 'Filter udara', 'Transmisi', 'Body & Aksesoris', 'Suspensi', 'Kabel & Selang'] as $cat)
                        <option value="{{ $cat }}" {{ $item->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold mb-1">Stok Saat Ini</label>
                <input type="number" name="stok" value="{{ $item->stok }}" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none">
                <p class="text-xs text-red-500 mt-1">*Edit stok manual hanya jika opname/selisih.</p>
            </div>

            <div>
                <label class="block font-bold mb-1">Harga Beli (Rp)</label>
                <input type="number" name="harga_beli" value="{{ $item->harga_beli }}" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none">
            </div>

            <div>
                <label class="block font-bold mb-1">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" value="{{ $item->harga_jual }}" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none">
            </div>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-1">Foto Barang</label>
            @if($item->foto && $item->foto != 'default.png')
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $item->foto) }}" class="h-20 w-20 object-cover border rounded">
                </div>
            @endif
            <input type="file" name="foto" class="w-full border border-gray-300 p-2 rounded">
            <p class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti foto.</p>
        </div>

        <button type="submit" class="w-full bg-racing-orange hover:bg-orange-700 text-white font-bold py-3 rounded shadow-lg uppercase tracking-wider">
            UPDATE DATA
        </button>
    </form>
</div>
@endsection