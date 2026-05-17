<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    
    protected $fillable = [
        'transaksi_id', 
        'item_id', 
        'nama_service', 
        'tipe', 
        'jumlah', 
        'harga_jual_saat_itu' 
    ];

    public function item()
    {
        // Menghubungkan Detail Transaksi dengan tabel Item
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}