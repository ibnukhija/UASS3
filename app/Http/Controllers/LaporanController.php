<?php
namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\RestockItem;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request) {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-d');
        $jenis = $request->jenis ?? 'masuk';

        if($jenis == 'keluar') {
            $data = Transaksi::with(['user', 'details.item'])
                    ->whereBetween('tanggal_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->get();
        } else {
            $data = RestockItem::with(['details.item'])
                    ->whereBetween('tanggal_masuk', [$startDate, $endDate])
                    ->get();
        }

        return view('laporan.index', compact('data', 'startDate', 'endDate', 'jenis'));
    }
}