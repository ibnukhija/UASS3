<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Users extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'nama',
        'username',
        'password',
    ];

    protected $hidden = ['password'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }
}

