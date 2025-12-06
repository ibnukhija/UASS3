<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'item_id';
    protected $guarded = [];

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }
}