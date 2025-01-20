<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retur extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $table = "retur";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;

    public $fillable = [
        "id_pesanan",
        "qty",
        "alasan",
        "processed_at",
    ];

    public function dtran()
    {
        return $this->belongsTo(Dtran::class, 'id_pesanan', 'id');
    }

    public function htran()
    {
        return $this->belongsTo(Htran::class, 'id_pesanan', 'id');
    }
}
