<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $table = "brands";
    protected $primaryKey = "ID_brands";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'logo',
        'description',
        'premium',
    ];
}
