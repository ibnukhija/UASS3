@extends('layout.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg border-t-4 border-racing-orange">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold uppercase text-gray-800"><i class="fa-solid fa-plus-circle"></i> Tambah Sparepart Baru</h2>
        <a href="{{ route('items.index') }}" class="text-gray-600 hover:text-gray-800">Kembali</a>
    </div>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="col-span-2">
                <label class="block font-bold mb-1">Nama Item</label>
                <input type="text" name="nama_item" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required placeholder="Contoh: Oli Yamalube Sport">
            </div>

            <div>
                <label class="block font-bold mb-1">Kategori</label>
                <select name="kategori" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none">
                    <option value="Oli & Pelumas">Oli & Pelumas</option>
                    <option value="Sistem Rem">Sistem Rem</option>
                    <option value="Kelistrikan">Kelistrikan</option>
                    <option value="Ban & Velg">Ban & Velg</option>
                    <option value="Filter udara">Filter udara</option>
                    <option value="Transmisi">Transmisi</option>
                    <option value="Body & Aksesoris">Body & Aksesoris</option>
                    <option value="Suspensi">Suspensi</option>
                    <option value="Kabel & Selang">Kabel & Selang</option>
                </select>
            </div>

            <div>
                <label class="block font-bold mb-1">Stok Awal</label>
                <input type="number" name="stok" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required value="0">
            </div>

            <div>
                <label class="block font-bold mb-1">Harga Beli (Rp)</label>
                <input type="number" name="harga_beli" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required placeholder="0">
            </div>

            <div>
                <label class="block font-bold mb-1">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required placeholder="0">
            </div>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-1">Foto Barang</label>
            <input type="file" name="foto" class="w-full border border-gray-300 p-2 rounded">
            <p class="text-xs text-gray-500">Format: JPG, PNG. Maks 2MB.</p>
        </div>

        <button type="submit" class="w-full bg-racing-orange hover:bg-orange-700 text-white 
        font-bold py-3 rounded shadow-lg uppercase tracking-wider">SIMPAN DATA</button>
    </form>
</div>
@endsection