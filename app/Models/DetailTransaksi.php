<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'detail_id';
    protected $guarded = [];

    public function item() {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}