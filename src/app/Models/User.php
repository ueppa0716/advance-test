<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        Billable,
        TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'authority',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function averageRating()
    {
        // Pro入会テストにて削除
        // return $this->hasOne(Reservation::class)
        //     ->selectRaw('shop_id, AVG(point) as average_rating')
        //     ->groupBy('shop_id');

        return $this->hasOne(Feedback::class)
            ->selectRaw('shop_id, AVG(point) as average_rating')
            ->where('status', '<>', 0)
            ->groupBy('shop_id');
    }



    // Pro入会テストにて追加 
    public function feedback()
    {
        return $this->hasMany(Like::class);
    }
}
