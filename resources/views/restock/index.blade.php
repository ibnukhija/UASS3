@extends('layout.app')

@section('content')
<div class="bg-white p-6 rounded shadow-lg border-t-4 border-gray-800">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('items.index') }}" class="text-gray-600 hover:text-racing-orange px-1 py-2 rounded">Kembali</a>
    </div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold uppercase"><i class="fa-solid fa-history"></i> Riwayat Restock</h2>
        <a href="{{ route('restock.create') }}" class="bg-green-600 text-white px-4 py-2 rounded font-bold hover:bg-green-700 shadow">+ Input Stok Baru</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Supplier</th>
                    <th class="p-3 text-left">Detail Barang</th>
                    <th class="p-3 text-left">Keterangan</th>
                    <th class="p-3 text-center">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($restocks as $restock)
                <tr class="border-b hover:bg-gray-50 align-top">
                    <td class="p-3">{{ $restock->tanggal_masuk }}</td>
                    <td class="p-3 font-bold">{{ $restock->nama_toko }}</td>
                    <td class="p-3">
                        <ul class="list-disc pl-4 text-sm">
                            @foreach($restock->details as $detail)
                                <li>
                                    <b>{{ $detail->item->nama_item ?? 'Barang Dihapus' }}</b> 
                                    <span class="text-gray-500">(x{{ $detail->jumlah }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="p-3 text-sm text-gray-500">{{ $restock->keterangan }}</td>
                    <td class="p-3 text-center">
                        <form action="{{ route('restock.destroy', $restock->restock_id) }}" method="POST" onsubmit="return confirm('Yakin hapus? Stok barang akan dikurangi kembali!')">
                            @csrf @method('DELETE')
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                <i class="fa-solid fa-trash"></i> Batal/Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $restocks->links() }}
    </div>
</div>
@endsection