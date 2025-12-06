<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Konstruktor untuk mengatur Locale di seluruh fungsi controller ini
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    public function index()
    {
        // 
        $totalBarang = Item::count();
        $stokMenipis = Item::where('stok', '<=', 5)->count();
        $pendapatanHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->sum('total_harga');

        //Transaksi Terakhir (Tabel)
        $transaksiTerbaru = Transaksi::with('user')
                                    ->orderBy('tanggal_transaksi', 'desc')
                                    ->limit(5)
                                    ->get();
        
        // Format tanggal tabel indonesia
        $transaksiTerbaru->transform(function ($trx) {
            $trx->tanggal_formatted = Carbon::parse($trx->tanggal_transaksi)->translatedFormat('l, d F Y');
            return $trx;
        });
        
        // Data Grafik
        $chartData = $this->getChartData('harian');

        return view('dashboard', compact(
            'totalBarang',
            'stokMenipis',
            'pendapatanHariIni',
            'transaksiTerbaru',
            'chartData'
        ));
    }

    // Fungsi untuk AJAX Request dari Grafik
    public function grafikPenjualan($filter)
    {
        $data = $this->getChartData($filter);
        return response()->json($data);
    }

    // Logic Utama Data Grafik
    private function getChartData($filter)
    {
        $labels = [];
        $data = [];
        $endDate = Carbon::now();

        if ($filter == 'harian') {
            // Data 7 hari terakhir
            $startDate = Carbon::now()->subDays(6);
            
            $transaksi = Transaksi::select(
                DB::raw('DATE(tanggal_transaksi) as date'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereBetween('tanggal_transaksi', [$startDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->pluck('total', 'date');

            for ($i = 0; $i <= 6; $i++) {
                $date = $startDate->copy()->addDays($i);

                // Translate ke indonesia
                $labels[] = $date->translatedFormat('d M'); 
                
                $dateKey = $date->format('Y-m-d'); // Key database tetap format standar
                $data[] = $transaksi[$dateKey] ?? 0;
            }

        } elseif ($filter == 'mingguan') {
            // Data 4 minggu terakhir
            for ($i = 3; $i >= 0; $i--) {
                $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                
                $total = Transaksi::whereBetween('tanggal_transaksi', [$startOfWeek, $endOfWeek])->sum('total_harga');

                // Label: Minggu ke-48
                $labels[] = 'Minggu ke-' . $startOfWeek->weekOfYear;
                $data[] = $total;
            }

        } elseif ($filter == 'bulanan') {
            // Data 12 bulan terakhir
            $transaksi = Transaksi::select(
                DB::raw('MONTH(tanggal_transaksi) as month'),
                DB::raw('YEAR(tanggal_transaksi) as year'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month', 'ASC')
            ->get();

            for ($i = 1; $i <= 12; $i++) {
                // translatedFormat('F') agar otomatis jadi "Desember"
                $monthName = Carbon::create()->month($i)->translatedFormat('F');
                $labels[] = $monthName;
                
                $found = $transaksi->where('month', $i)->first();
                $data[] = $found ? $found->total : 0;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}