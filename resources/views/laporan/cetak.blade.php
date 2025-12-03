<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Speed Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background-color: white; -webkit-print-color-adjust: exact; }
        }
        body { font-family: 'Times New Roman', Times, serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="no-print fixed top-0 left-0 w-full bg-gray-800 p-4 flex justify-between shadow-lg">
        <a href="{{ route('laporan.index') }}" class="text-white font-bold hover:underline">&larr; Kembali</a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700">
            <i class="fa-solid fa-print"></i> CETAK DOKUMEN
        </button>
    </div>

    <div class="bg-white max-w-4xl mx-auto mt-20 mb-10 p-10 shadow-xl border print:shadow-none print:border-none print:m-0 print:w-full">
        
        <div class="text-center border-b-4 border-double border-black pb-4 mb-6">
            <h1 class="text-3xl font-black uppercase tracking-wider">HUSNA OLI KEDIRI</h1>
            <p class="text-sm text-gray-600">Jl. Raya Kediri No. 123, Jawa Timur | Telp: 0812-3456-7890</p>
            <p class="text-sm text-gray-600">Spesialis Sparepart Motor & Aksesoris Racing</p>
        </div>

        <div class="text-center mb-6">
            <h2 class="text-xl font-bold uppercase underline">LAPORAN {{ $jenis == 'keluar' ? 'PENJUALAN (BARANG KELUAR)' : 'RESTOCK (BARANG MASUK)' }}</h2>
            <p class="mt-1">Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
        </div>

        <table class="w-full border-collapse border border-black text-sm">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-black p-2">No</th>
                    <th class="border border-black p-2">Tanggal</th>
                    @if($jenis == 'keluar')
                        <th class="border border-black p-2">Kasir</th>
                        <th class="border border-black p-2">Detail Barang</th>
                        <th class="border border-black p-2 text-right">Total (Rp)</th>
                    @else
                        <th class="border border-black p-2">Supplier</th>
                        <th class="border border-black p-2">Detail Barang</th>
                        <th class="border border-black p-2 text-right">Modal (Rp)</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; $no = 1; @endphp
                @forelse($data as $row)
                    @php 
                        if($jenis == 'keluar'){
                            $totalRow = $row->total_harga;
                        } else {
                            $totalRow = 0;
                            foreach($row->details as $d) { $totalRow += $d->jumlah * $d->harga_beli_saat_itu; }
                        }
                        $grandTotal += $totalRow;
                    @endphp
                    <tr>
                        <td class="border border-black p-2 text-center">{{ $no++ }}</td>
                        <td class="border border-black p-2 text-center">
                            {{ $jenis == 'keluar' ? date('d/m/Y H:i', strtotime($row->tanggal_transaksi)) : date('d/m/Y', strtotime($row->tanggal_masuk)) }}
                        </td>
                        
                        @if($jenis == 'keluar')
                            <td class="border border-black p-2">{{ $row->user->nama ?? '-' }}</td>
                        @else
                            <td class="border border-black p-2">{{ $row->nama_toko }}</td>
                        @endif

                        <td class="border border-black p-2">
                            <ul class="list-none">
                                @foreach($row->details as $d)
                                    <li>- {{ $d->item->nama_item }} ({{ $d->jumlah }} pcs)</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="border border-black p-2 text-right">{{ number_format($totalRow, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="border border-black p-4 text-center italic">Tidak ada data pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="font-bold bg-gray-100">
                    <td colspan="4" class="border border-black p-2 text-right text-lg">GRAND TOTAL</td>
                    <td class="border border-black p-2 text-right text-lg">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="flex justify-end mt-16">
            <div class="text-center w-64">
                <p>Kediri, {{ date('d F Y') }}</p>
                <p class="mb-20">Mengetahui, Owner</p>
                <p class="font-bold border-b border-black inline-block min-w-[200px]">{{ Auth::user()->nama ?? 'Owner' }}</p>
            </div>
        </div>

    </div>

    <script>
        // Opsional: Otomatis print saat halaman dibuka
        // window.print();
    </script>
</body>
</html>