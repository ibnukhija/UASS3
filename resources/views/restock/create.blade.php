@extends('layout.app')

@section('content')
<div class="bg-white p-6 rounded shadow-lg border-t-4 border-green-600">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold uppercase text-gray-800"><i class="fa-solid fa-truck-ramp-box"></i> Input Barang Masuk</h2>
        <a href="{{ route('restock.index') }}" class="text-gray-500 hover:text-racing-orange px-1 py-2 rounded">Lihat History</a>
    </div>

    <form action="{{ route('restock.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded border">
            <div>
                <label class="block font-bold text-sm mb-1">Nama Supplier / Toko</label>
                <input type="text" name="nama_toko" class="w-full border p-2 rounded" placeholder="Contoh: AHM Pusat" required>
            </div>
            <div>
                <label class="block font-bold text-sm mb-1">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-bold text-sm mb-1">Keterangan (Opsional)</label>
                <input type="text" name="keterangan" class="w-full border p-2 rounded" placeholder="Catatan...">
            </div>
        </div>

        <h3 class="font-bold mb-2 text-lg">Daftar Barang</h3>
        <table class="w-full border-collapse mb-4" id="tableRestock">
            <thead>
                <tr class="bg-gray-800 text-white text-sm">
                    <th class="p-2 text-left w-1/2">Nama Barang</th>
                    <th class="p-2 text-left w-1/4">Harga Beli (Satuan)</th>
                    <th class="p-2 text-left w-1/6">Jumlah Masuk</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="restockRows">
                <tr class="border-b">
                    <td class="p-2">
                        <select name="items[0][item_id]" class="w-full border p-2 rounded item-select" onchange="updatePrice(this)" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->item_id }}" data-price="{{ $item->harga_beli }}">{{ $item->nama_item }} (Stok: {{ $item->stok }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-2">
                        <input type="number" name="items[0][harga_beli]" class="w-full border p-2 rounded price-input" placeholder="0" required>
                    </td>
                    <td class="p-2">
                        <input type="number" name="items[0][jumlah]" class="w-full border p-2 rounded" placeholder="0" min="1" required>
                    </td>
                    <td class="p-2 text-center">
                        <button type="button" class="text-red-500 disabled:opacity-50" disabled><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex gap-2 mb-6">
            <button type="button" onclick="addRow()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-bold">
                <i class="fa-solid fa-plus"></i> Tambah Baris
            </button>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded shadow-lg uppercase tracking-wider">
            SIMPAN STOK MASUK
        </button>
    </form>
</div>

<script>
    let rowIndex = 0;

    function addRow() {
        rowIndex++;
        const template = `
            <tr class="border-b" id="row-${rowIndex}">
                <td class="p-2">
                    <select name="items[${rowIndex}][item_id]" class="w-full border p-2 rounded item-select" onchange="updatePrice(this)" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->item_id }}" data-price="{{ $item->harga_beli }}">{{ $item->nama_item }} (Stok: {{ $item->stok }})</option>
                        @endforeach
                    </select>
                </td>
                <td class="p-2">
                    <input type="number" name="items[${rowIndex}][harga_beli]" class="w-full border p-2 rounded price-input" placeholder="0" required>
                </td>
                <td class="p-2">
                    <input type="number" name="items[${rowIndex}][jumlah]" class="w-full border p-2 rounded" placeholder="0" min="1" required>
                </td>
                <td class="p-2 text-center">
                    <button type="button" onclick="removeRow(${rowIndex})" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
        document.getElementById('restockRows').insertAdjacentHTML('beforeend', template);
    }

    function removeRow(index) {
        document.getElementById(`row-${index}`).remove();
    }

    // Auto-fill harga beli saat barang dipilih
    function updatePrice(selectElement) {
        const price = selectElement.options[selectElement.selectedIndex].getAttribute('data-price');
        // Cari input harga di baris yang sama
        const row = selectElement.closest('tr');
        const priceInput = row.querySelector('.price-input');
        if(price) {
            priceInput.value = price;
        }
    }
</script>
@endsection