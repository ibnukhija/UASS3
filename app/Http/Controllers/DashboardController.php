<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    public function index()
    {
        // Statistik utama
        $totalBarang = Item::count();
        $stokMenipis = Item::where('stok', '<=', 5)->count();
        $pendapatanHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->sum('total_harga');

        // Transaksi terakhir
        $transaksiTerbaru = Transaksi::with('user')->orderBy('tanggal_transaksi', 'desc')->limit(5)->get();
        $transaksiTerbaru->transform(function ($trx) {
            $trx->tanggal_formatted = Carbon::parse($trx->tanggal_transaksi)->translatedFormat('l, d F Y');
            return $trx;
        });
        
        // Data untuk grafik
        $chartData = $this->getChartData('harian');
        
        // Data barang terlaris
        $topItems = $this->getTopSellingItems();

        return view('dashboard', compact(
            'totalBarang',
            'stokMenipis',
            'pendapatanHariIni',
            'transaksiTerbaru',
            'chartData',
            'topItems'
        ));
    }

    // Mengambil 5 barang dengan penjualan tertinggi
    private function getTopSellingItems()
    {
        return DetailTransaksi::where('tipe', 'barang')
            ->select('item_id', DB::raw('SUM(jumlah) as total_sold'))
            ->groupBy('item_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->with('item')
            ->get();
    }

    public function grafikPenjualan($filter)
    {
        return response()->json($this->getChartData($filter));
    }

    private function getChartData($filter)
    {
        $labels = [];
        $data = [];
        $endDate = Carbon::now();

        if ($filter == 'harian') {
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
                $labels[] = $date->translatedFormat('d M'); 
                $data[] = $transaksi[$date->format('Y-m-d')] ?? 0;
            }

        } elseif ($filter == 'mingguan') {
            for ($i = 3; $i >= 0; $i--) {
                $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                $total = Transaksi::whereBetween('tanggal_transaksi', [$startOfWeek, $endOfWeek])->sum('total_harga');
                $labels[] = 'Minggu ke-' . $startOfWeek->weekOfYear;
                $data[] = $total;
            }

        } elseif ($filter == 'bulanan') {
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
                $monthName = Carbon::create()->month($i)->translatedFormat('F');
                $labels[] = $monthName;
                $found = $transaksi->where('month', $i)->first();
                $data[] = $found ? $found->total : 0;
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }
}