<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetailRestockItems extends Model
{
    protected $table = 'detail_restock_items';
    protected $primaryKey = 'detail_id';
    protected $guarded = [];

    public function item() {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}