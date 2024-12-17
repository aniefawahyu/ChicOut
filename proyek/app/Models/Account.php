<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    use HasFactory;

    protected $connection = "mysql";
    protected $table = "accounts";
    protected $primaryKey = "username";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'username',
        'display_name',
        'email',
        'password',
        'tel',
        'address',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function getHtrans()
    {
        return $this->hasMany(Htran::class, 'username', 'username');
    }

    public function getCart()
    {
        return $this->hasMany(Cart::class, 'username', 'username');
    }

    public function myReviews()
    {
        return $this->hasMany(Review::class, 'username', 'username');
    }
}
