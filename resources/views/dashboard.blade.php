@extends('layout.app')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">

        <!-- {{-- Total Jenis Barang Card --}} -->
        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-gray-800 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-bold uppercase">Total Jenis Barang</p>
                <h3 class="text-2xl md:text-3xl font-black text-gray-800">
                    {{ $totalBarang }}
                </h3>
            </div>
            <i class="fa-solid fa-boxes-stacked text-3xl md:text-4xl text-gray-400"></i>
        </div>

        <!-- {{-- Stok Menipis Card --}} -->
        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-red-600 flex items-center justify-between">
            <div>
                <p class="text-red-600 text-sm font-bold uppercase">Stok &lt; 5</p>
                <h3 class="text-2xl md:text-3xl font-black text-red-600">
                    {{ $stokMenipis }}
                </h3>
            </div>
            <i class="fa-solid fa-triangle-exclamation text-3xl md:text-4xl text-red-400"></i>
        </div>

        <!-- {{-- Omset Hari Ini Card --}} -->
        <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-600 flex items-center justify-between">
            <div>
                <p class="text-green-600 text-sm font-bold uppercase">Omset Hari Ini</p>
                <h3 class="text-xl md:text-3xl font-black text-green-700">
                    Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                </h3>
            </div>
            <i class="fa-solid fa-coins text-3xl md:text-4xl text-green-400"></i>
        </div>
    </div>
    
    <hr class="border-gray-300 mb-8">

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- {{-- Kelola Barang Card --}} -->
        <a href="{{ route('items.index') }}" class="group block">
            <div class="bg-white border-4 border-gray-800 hover:border-racing-orange p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-56 flex flex-col justify-center items-center">
                <i class="fa-solid fa-wrench text-4xl md:text-5xl text-gray-800 mb-4 group-hover:text-gray-500 transition"></i>
                <h2 class="text-xl md:text-2xl font-black uppercase text-gray-900">Kelola Barang</h2>
            </div>
        </a>

        <!-- {{-- Laporan Card --}} -->
        <a href="{{ route('laporan.index') }}" class="group block">
            <div class="bg-white border-4 border-blue-800 hover:border-blue-500 p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-56 flex flex-col justify-center items-center">
                <i class="fa-solid fa-file-invoice-dollar text-4xl md:text-5xl text-blue-800 mb-4 group-hover:text-blue-500 transition"></i>
                <h2 class="text-xl md:text-2xl font-black uppercase text-blue-900">Laporan</h2>
            </div>
        </a>

        <!-- {{-- Kasir Card --}} -->
        <a href="{{ route('kasir') }}" class="group block">
            <div class="bg-white border-4 border-red-800 hover:border-red-500 p-6 rounded-xl shadow-xl transition transform hover:-translate-y-2 text-center h-56 flex flex-col justify-center items-center">
                <i class="fa-solid fa-cash-register text-4xl md:text-5xl text-red-800 mb-4 group-hover:text-red-500 transition"></i>
                <h2 class="text-xl md:text-2xl font-black uppercase text-red-900">KASIR</h2>
            </div>
        </a>
    </div>

    <div class="mt-10 bg-white p-4 rounded shadow-lg">
        <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Transaksi Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Kasir</th>
                        <th class="px-4 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $t)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">
                                {{ $t->tanggal_formatted ?? $t->tanggal_transaksi }}
                            </td>
                            <td class="px-4 py-2">{{ $t->user->nama ?? 'Unknown' }}</td>
                            <td class="px-4 py-2 text-right font-bold text-gray-900">
                                Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-center text-gray-500">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- // Grafik Penjualan -->
    <div class="bg-white p-4 rounded-lg shadow-lg mt-10">
        <div class="flex flex-col md:flex-row justify-between gap-3 mb-4 items-center">
            <h3 class="font-bold text-gray-700 text-lg">Grafik Penjualan</h3>
            <select id="filterPenjualan" class="border border-gray-300 rounded px-4 py-2 w-full md:w-48 focus:ring-blue-500 focus:border-blue-500">
                <option value="harian">Harian</option>
                <option value="mingguan">Mingguan</option>
                <option value="bulanan">Bulanan</option>
            </select>
        </div>
        <div class="relative w-full h-72">
            <canvas id="grafikPenjualan"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('grafikPenjualan');

        //
        let initialLabels = @json($chartData['labels']);
        let initialData = @json($chartData['data']);

        // untuk membuat grafik
        let chartPenjualan = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialLabels, 
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: initialData, 
                    backgroundColor: 'rgba(30, 64, 175, 0.2)', 
                    borderColor: 'rgb(30, 64, 175)', 
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(30, 64, 175)'
                }]
            },

            // untuk membuat grafik menjadi responsive
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Fungsi untuk mengambil data grafik berdasarkan filter
        document.getElementById('filterPenjualan').addEventListener('change', function() {
            const filterType = this.value;
            fetch('/grafik-penjualan/' + filterType)
                .then(response => response.json())
                .then(data => {
                    chartPenjualan.data.labels = data.labels;
                    chartPenjualan.data.datasets[0].data = data.data;
                    chartPenjualan.data.datasets[0].label = 'Penjualan (' + filterType.charAt(0).toUpperCase() + filterType.slice(1) + ') (Rp)';
                    chartPenjualan.update();
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection