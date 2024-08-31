<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'location_id',
        'category_id',
        'user_id',
        'detail',
        'photo',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function averageRating()
    {
        return $this->hasMany(Reservation::class, 'shop_id')
            ->selectRaw('shop_id, AVG(point) as average_rating')
            ->groupBy('shop_id');
    }
}
