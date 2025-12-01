<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Item::count();

        $stokMenipis = Item::where('stok', '<=', 5)->count();

        $pendapatanHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())
                                ->sum('total_harga');

        $harian = DB::table('transaksi')
                    ->selectRaw('DATE(tanggal_transaksi) as tanggal, SUM(total_harga) as total')
                    ->whereBetween('tanggal_transaksi', [
                        Carbon::now()->subDays(6)->startOfDay(),
                        Carbon::now()->endOfDay()
                    ])
                    ->groupBy('tanggal')
                    ->orderBy('tanggal', 'ASC')
                    ->get();

        $labelHarian = $harian->pluck('tanggal');
        $dataHarian  = $harian->pluck('total');

        $transaksiTerbaru = Transaksi::with('user')
                            ->orderBy('tanggal_transaksi', 'desc')
                            ->limit(5)
                            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'stokMenipis',
            'pendapatanHariIni',
            'transaksiTerbaru',
            'labelHarian',
            'dataHarian'
        ));
    }
}
