<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $connection = "mysql";
    protected $table = "cart";
    protected $primaryKey = "ID_cart";
    public $incrementing = true;
    public $timestamps = false;


    public $fillable = [
        "username",
        "ID_items",
        "qty",
    ];

    public function Item() {
        return $this->belongsTo(Item::class, "ID_items", "ID_items");
    }

    public function Account(){
        return $this->belongsTo(Account::class, "username", "username");
    }
}
