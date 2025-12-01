@extends('layout.app')
@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-gray-800 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm font-bold uppercase">Total Jenis Barang</p>
            <h3 class="text-3xl font-black text-gray-800">{{ $totalBarang }}</h3>
        </div>
        <i class="fa-solid fa-boxes-stacked text-4xl text-gray-400"></i>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-600 flex items-center justify-between">
        <div>
            <p class="text-red-600 text-sm font-bold uppercase">Stok Menipis (< 5)</p>
            <h3 class="text-3xl font-black text-red-600">{{ $stokMenipis }}</h3>
        </div>
        <i class="fa-solid fa-triangle-exclamation text-4xl text-red-400"></i>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-600 flex items-center justify-between">
        <div>
            <p class="text-green-600 text-sm font-bold uppercase">Omset Hari Ini</p>
            <h3 class="text-3xl font-black text-green-700">Rp {{ number_format($pendapatanHariIni) }}</h3>
        </div>
        <i class="fa-solid fa-coins text-4xl text-green-400"></i>
    </div>
</div>

<hr class="border-gray-300 mb-8">

<div class="grid grid-cols-1 md:grid-cols-3 gap-6"> 
    <a href="{{ route('items.index') }}" class="group block">
        <div class="bg-white border-4 border-gray-800 hover:border-racing-orange p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-64 flex flex-col justify-center items-center">
            <i class="fa-solid fa-wrench text-5xl text-gray-800 group-hover:text-gray-500 mb-4"></i>
            <h2 class="text-2xl font-black uppercase text-gray-900">Kelola Barang</h2>
            <p class="text-gray-700 mt-2 text-sm">Database & Stok</p>
        </div>
    </a>

    <a href="{{ route('laporan.index') }}" class="group block">
        <div class="bg-white border-4 border-blue-800 hover:border-blue-500 p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-64 flex flex-col justify-center items-center">
            <i class="fa-solid fa-file-invoice-dollar text-5xl text-blue-800 group-hover:text-blue-500 mb-4"></i>
            <h2 class="text-2xl font-black uppercase text-blue-900">Laporan</h2>
            <p class="text-gray-700 mt-2 text-sm">Rekap Masuk & Keluar</p>
        </div>
    </a>

    <a href="{{ route('kasir') }}" class="group block">
        <div class="bg-white bg-to-br from-racing-orange to-red-600 border-4 border-red-800 p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-64 flex flex-col justify-center items-center text-white">
            <i class="fa-solid fa-cash-register text-5xl text-red-800 group-hover:text-red-500 mb-4"></i>
            <h2 class="text-2xl font-black uppercase text-red-900">KASIR</h2>
            <p class="text-red-500 mt-2 text-sm">Input Penjualan</p>
        </div>
    </a>
</div>

<div class="mt-10 bg-white p-6 rounded shadow-lg">
    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Transaksi Terakhir</h3>
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Kasir</th>
                <th class="px-6 py-3 text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksiTerbaru as $t)
            <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4">{{ $t->tanggal_transaksi }}</td>
                <td class="px-6 py-4">{{ $t->user->nama ?? 'Unknown' }}</td>
                <td class="px-6 py-4 text-right font-bold text-gray-900">Rp {{ number_format($t->total_harga) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-4 text-center">Belum ada transaksi hari ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection