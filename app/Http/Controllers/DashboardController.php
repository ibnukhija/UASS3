<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Item::count();

        $stokMenipis = Item::where('stok', '<=', 5)->count();

        $pendapatanHariIni = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->sum('total_harga');

        $transaksiTerbaru = Transaksi::with('user')->orderBy('tanggal_transaksi', 'desc')->take(5)->get();

        return view('dashboard', compact('totalBarang', 'stokMenipis', 'pendapatanHariIni', 'transaksiTerbaru'));
    }
}