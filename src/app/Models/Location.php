<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'location',
    ];

    public function shop()
    {
        return $this->hasMany(Shop::class);
    }
}
