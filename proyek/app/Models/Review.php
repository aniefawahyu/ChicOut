<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $table = "reviews";
    protected $primaryKey = "ID_reviews";
    public $incrementing = true;
    public $timestamps = true;

    public $fillable = [
        "username",
        "ID_items",
        "rating",
        "comment",
    ];

    public function Item(){
        return $this->belongsTo(Item::class, "ID_items", "ID_items");
    }

    public function Account(){
        return $this->belongsTo(Account::class, "username", "username");
    }

}
