@extends('layout.app')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-gray-800 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Total Jenis Barang</p>
                <h3 class="text-2xl md:text-3xl font-black text-gray-800">{{ $totalBarang }}</h3>
            </div>
            <i class="fa-solid fa-boxes-stacked text-3xl md:text-4xl text-gray-400"></i>
        </div>

        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-red-600 flex items-center justify-between">
            <div>
                <p class="text-red-600 text-sm font-bold uppercase">Stok < 5</p>
                <h3 class="text-2xl md:text-3xl font-black text-red-600">{{ $stokMenipis }}</h3>
            </div>
            <i class="fa-solid fa-triangle-exclamation text-3xl md:text-4xl text-red-400"></i>
        </div>

        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-600 flex items-center justify-between">
            <div>
                <p class="text-green-600 text-sm font-bold uppercase">Omset Hari Ini</p>
                <h3 class="text-xl md:text-3xl font-black text-green-700">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
            </div>
            <i class="fa-solid fa-coins text-3xl md:text-4xl text-green-400"></i>
        </div>
    </div>
    
    <hr class="border-gray-300 mb-8">
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <a href="{{ route('users.index') }}" class="group block">
            <div class="bg-white border-4 border-gray-800 hover:border-gray-500 p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-56 flex flex-col justify-center items-center">
                <i class="fa-solid fa-users text-4xl md:text-5xl text-gray-800 mb-4 group-hover:text-gray-500 transition"></i>
                <h2 class="text-xl md:text-2xl font-black uppercase text-gray-900">Kelola User</h2>
            </div>
        </a>
        <a href="{{ route('items.index') }}" class="group block">
            <div class="bg-white border-4 border-gray-800 hover:border-gray-500 p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-56 flex flex-col justify-center items-center">
                <i class="fa-solid fa-wrench text-4xl md:text-5xl text-gray-800 mb-4 group-hover:text-gray-500 transition"></i>
                <h2 class="text-xl md:text-2xl font-black uppercase text-gray-900">Kelola Barang</h2>
            </div>
        </a>
        <a href="{{ route('laporan.index') }}" class="group block">
            <div class="bg-white border-4 border-gray-800 hover:border-gray-500 p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-56 flex flex-col justify-center items-center">
                <i class="fa-solid fa-file-invoice-dollar text-4xl md:text-5xl text-gray-800 mb-4 group-hover:text-gray-500 transition"></i>
                <h2 class="text-xl md:text-2xl font-black uppercase text-gray-900">Laporan</h2>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-10">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="flex justify-between mb-4 items-center">
                <h3 class="font-bold text-gray-700 text-lg">Grafik Penjualan</h3>
                <select id="filterPenjualan" class="border border-gray-300 rounded px-4 py-2 w-32">
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>
            <div class="relative w-full h-72">
                <canvas id="grafikPenjualan"></canvas>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-lg">
            <h3 class="font-bold text-gray-700 text-lg mb-4">Top 5 Barang Terlaris</h3>
            <div class="relative w-full h-72">
                <canvas id="grafikTerlaris"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Inisialisasi Grafik Penjualan
        const ctx = document.getElementById('grafikPenjualan');
        let chartPenjualan = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: @json($chartData['data']),
                    backgroundColor: 'rgba(30, 64, 175, 0.2)',
                    borderColor: 'rgb(30, 64, 175)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } }
                }
            }
        });

        // Filter Grafik Penjualan
        document.getElementById('filterPenjualan').addEventListener('change', function() {
            fetch('/grafik-penjualan/' + this.value)
                .then(res => res.json())
                .then(data => {
                    chartPenjualan.data.labels = data.labels;
                    chartPenjualan.data.datasets[0].data = data.data;
                    chartPenjualan.update();
                });
        });

        // Inisialisasi Grafik Barang Terlaris
        new Chart(document.getElementById('grafikTerlaris'), {
            type: 'bar',
            data: {
                labels: @json($topItems->pluck('item.nama_item')),
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: @json($topItems->pluck('total_sold')),
                    backgroundColor: 'rgba(255, 94, 0, 0.7)',
                    borderColor: 'rgb(255, 94, 0)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    </script>
@endsection