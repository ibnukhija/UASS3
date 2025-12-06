@extends('layout.app')

@section('content')
<div class="bg-white p-6 rounded shadow-lg border-t-4 border-blue-600">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold uppercase mb-6"><i class="fa-solid fa-file-invoice"></i> Laporan Bengkel</h2>
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">Kembali</a>
        </div>
    </div>    
    <form action="{{ route('laporan.index') }}" method="GET" class="bg-gray-100 p-4 rounded mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block font-bold text-xs mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="border p-2 rounded">
        </div>
        <div>
            <label class="block font-bold text-xs mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="border p-2 rounded">
        </div>
        <div>
            <label class="block font-bold text-xs mb-1">Jenis Laporan</label>
            <select name="jenis" class="border p-2 rounded w-40">
                <option value="masuk" {{ $jenis == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                <option value="keluar" {{ $jenis == 'keluar' ? 'selected' : '' }}>Penjualan (Keluar)</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        <button type="button" onclick="window.print()" class="bg-gray-600 text-white px-2 py-2 rounded font-bold hover:bg-gray-700">
            <a href="{{ route('laporan.cetak', ['start_date' => $startDate, 'end_date' => $endDate, 'jenis' => $jenis]) }}" target="_blank" 
                class="bg-gray-800 text-white px-4 py-2 rounded font-bold hover:bg-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak PDF
            </a>
        </button>
    </form>

    <div class="overflow-x-auto">
        @if($jenis == 'keluar')
            <h3 class="font-bold mb-2 text-green-700">Laporan Penjualan (Sparepart Keluar)</h3>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">Tanggal Transaksi</th>
                        <th class="border p-2">Kasir</th>
                        <th class="border p-2">Item Terjual</th>
                        <th class="border p-2 text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @forelse($data as $row)
                        @php $grandTotal += $row->total_harga; @endphp
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="border p-2 text-center">{{ $row->tanggal_transaksi }}</td>
                            <td class="border p-2">{{ $row->user->nama ?? 'Unknown' }}</td>
                            <td class="border p-2 text-sm">
                                <ul class="list-disc pl-4">
                                    @foreach($row->details as $d)
                                        <li>{{ $d->item->nama_item }} ({{ $d->jumlah }} pcs)</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border p-2 text-right font-bold">Rp {{ number_format($row->total_harga) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-gray-500">Tidak ada data penjualan saat ini.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="3" class="border p-2 text-center">TOTAL PENDAPATAN</td>
                        <td class="border p-2 text-right text-green-700">Rp {{ number_format($grandTotal) }}</td>
                    </tr>
                </tfoot>
            </table>

        @else
            <h3 class="font-bold mb-2 text-green-700">Laporan Restock (Barang Masuk)</h3>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">Tanggal</th>
                        <th class="border p-2">Supplier</th>
                        <th class="border p-2">Detail Barang Masuk</th>
                        <th class="border p-2">Total Modal (Estimasi)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotalModal = 0; @endphp
                    @forelse($data as $row)
                        @php 
                            $rowTotal = 0; 
                            foreach($row->details as $d) { $rowTotal += $d->jumlah * $d->harga_beli_saat_itu; }
                            $grandTotalModal += $rowTotal;
                        @endphp
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="border p-2 text-center">
                                {{ $row->tanggal_masuk ? date('d/m/Y', strtotime($row->tanggal_masuk)) : '-' }}
                            </td>
                            
                            <td class="border p-2">{{ $row->nama_toko }}</td>
                            <td class="border p-2 text-sm">
                                <ul class="list-disc pl-4">
                                    @foreach($row->details as $d)
                                        <li>
                                            {{ $d->item->nama_item ?? 'Barang Dihapus' }} : 
                                            {{ $d->jumlah }} pcs 
                                            (Rp {{ number_format($d->harga_beli_saat_itu, 0, ',', '.') }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border p-2 text-right font-bold">Rp {{ number_format($rowTotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-4 text-center text-gray-500">Tidak ada data barang masuk saat ini.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="3" class="border p-2 text-center">TOTAL PENGELUARAN MODAL</td>
                        <td class="border p-2 text-right text-red-700">Rp {{ number_format($grandTotalModal) }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
</div>
@endsection