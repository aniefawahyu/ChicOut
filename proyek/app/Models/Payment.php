<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $table = "payments";
    protected $primaryKey = "ID_payments";
    public $incrementing = true;
    public $timestamps = true;

    public $fillable = [
        "name",
        "img",
    ];
}
