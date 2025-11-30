<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailRestockItems;

class RestockItem extends Model
{
    protected $table = 'restock_items';
    protected $primaryKey = 'restock_id';
    protected $guarded = [];

    public function details() {
        return $this->hasMany(DetailRestockItems::class, 'restock_id', 'restock_id');
    }
}