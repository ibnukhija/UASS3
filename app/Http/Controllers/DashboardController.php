<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Fungsi carbon untuk memanipulasi tanggal

class DashboardController extends Controller
{
    public function index()
    {
        //mengubah locale ke Indonesia
        Carbon::setLocale('id');

        // Menghitung jumlah barang
        $totalBarang = Item::count();
        $stokMenipis = Item::where('stok', '<=', 5)->count();
        $pendapatanHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())
                                ->sum('total_harga');

        // Mengambil 5 transaksi terbaru
        $transaksiTerbaru = Transaksi::with('user')
                            ->orderBy('tanggal_transaksi', 'desc')
                            ->limit(5)
                            ->get();
        
        // Mengubah format tanggal
        $transaksiTerbaru->transform(function ($trx) {
            $trx->tanggal_formatted = Carbon::parse($trx->tanggal_transaksi)->translatedFormat('l, d F Y');
            return $trx;
        });
        
        $chartData = $this->getChartData('harian');

        return view('dashboard', compact(
            'totalBarang',
            'stokMenipis',
            'pendapatanHariIni',
            'transaksiTerbaru',
            'chartData'
        ));
    }

    public function grafikPenjualan($filter)
    {
        $data = $this->getChartData($filter);
        return response()->json($data);
    }

    private function getChartData($filter)
    {
        $labels = [];
        $data = [];
        $endDate = Carbon::now();

        if ($filter == 'harian') {
            // Data 7 hari terakhir
            $startDate = Carbon::now()->subDays(6);
            
            // Mengambil data dari DB dikelompokkan per tanggal
            $transaksi = Transaksi::select(
                DB::raw('DATE(tanggal_transaksi) as date'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereBetween('tanggal_transaksi', [$startDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->pluck('total', 'date');

            // Loop 7 hari untuk memastikan tanggal yang 0 rupiah tetap muncul
            for ($i = 0; $i <= 6; $i++) {
                $date = $startDate->copy()->addDays($i);
                
                $dateStr = $date->format('d M');
                
                //translate ke indonesia
                $labels[] = $this->formatIndo($dateStr); 
                
                $dateKey = $date->format('Y-m-d');
                $data[] = $transaksi[$dateKey] ?? 0;
            }

        } elseif ($filter == 'mingguan') {
            // Data 4 minggu terakhir
            for ($i = 3; $i >= 0; $i--) {
                $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                $total = Transaksi::whereBetween('tanggal_transaksi', [$startOfWeek, $endOfWeek])->sum('total_harga');

                $labels[] = 'Minggu ke-' . $startOfWeek->weekOfYear;
                $data[] = $total;
            }

        } elseif ($filter == 'bulanan') {
            // Data 12 bulan terakhir (Jan - Des tahun ini)
            $transaksi = Transaksi::select(
                DB::raw('MONTH(tanggal_transaksi) as month'),
                DB::raw('YEAR(tanggal_transaksi) as year'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month', 'ASC')
            ->get();

            // Loop 12 Bulan
            for ($i = 1; $i <= 12; $i++) {
                $monthNameEn = Carbon::create()->month($i)->format('F');
                $labels[] = $this->formatIndo($monthNameEn);
                
                // Cari data bulan ini
                $found = $transaksi->where('month', $i)->first();
                $data[] = $found ? $found->total : 0;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    // Mengubah nama bulan ke indonesia
    private function formatIndo($string) {
        $bulanEn = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        $bulanId = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

        return str_replace($bulanEn, $bulanId, $string);
    }
}
