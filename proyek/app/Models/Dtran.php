<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dtran extends Model
{
    use HasFactory;
    protected $connection = "mysql";
    protected $table = "dtrans";
    protected $primaryKey = "ID_dtrans";
    public $incrementing = true;
    public $timestamps = false;

    public $fillable = [
        "ID_htrans",
        "ID_items",
        "qty",
        "price",
        "discount",
        "subtotal",
    ];

    public function Item() {
        return $this->belongsTo(Item::class, "ID_items", "ID_items")->withTrashed();
    }

    public function Htrans() {
        return $this->belongsTo(Htran::class, "ID_htrans", "ID_htrans");
    }
}
