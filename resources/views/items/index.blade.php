@extends('layout.app')

@section('content')
<div class="bg-white p-6 rounded shadow-lg border-t-4 border-gray-800">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 border border-gray-500 hover:border-gray-700 px-4 py-2 rounded">Kembali</a>
    </div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold uppercase"><i class="fa-solid fa-list"></i> Data Sparepart</h2>
        <a href="{{ route('items.create') }}" class="bg-racing-orange text-white px-4 py-2 rounded font-bold hover:bg-orange-700 shadow">+ Tambah Barang</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="p-3 text-left">Nama Item</th>
                    <th class="p-3 text-left">Kategori</th>
                    <th class="p-3 text-right">Harga Beli</th>
                    <th class="p-3 text-right">Harga Jual</th>
                    <th class="p-3 text-center">Stok</th>
                    <th class="p-3 text-center">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold">{{ $item->nama_item }}</td>
                    <td class="p-3"><span class="bg-gray-200 text-xs px-2 py-1 rounded">{{ $item->kategori }}</span></td>
                    <td class="p-3 text-right text-gray-500">Rp {{ number_format($item->harga_beli) }}</td>
                    <td class="p-3 text-right font-bold text-racing-orange">Rp {{ number_format($item->harga_jual) }}</td>
                    <td class="p-3 text-center">
                        <span class="{{ $item->stok < 5 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                            {{ $item->stok }}
                        </span>
                    </td>
                    <td class="p-3 text-center flex gap-2 justify-center">
                        <a href="{{ route('items.edit', $item->item_id) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Edit</a>
                        <form action="{{ route('items.destroy', $item->item_id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
@endsection