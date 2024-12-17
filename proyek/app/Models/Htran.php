<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Htran extends Model
{
    use HasFactory;
    protected $connection = "mysql";
    protected $table = "htrans";
    protected $primaryKey = "ID_htrans";
    public $incrementing = true;
    public $timestamps = false;

    public $fillable = [
        "username",
        "ID_payments",
        "total",
        "address",
    ];

    public function Account() {
        return $this->belongsTo(Account::class, "username", "username");
    }

    public function getDtrans() {
        return $this->hasMany(Dtran::class, "ID_htrans", "ID_htrans");
    }

    public function Payment() {
        return $this->belongsTo(Payment::class, "ID_payments", "ID_payments")->withTrashed();
    }

    public function scopeTotalByDay($query, $startDate, $endDate)
    {
        return $query
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->groupByRaw('DATE(purchase_date)')
            ->selectRaw('DATE(purchase_date) as purchase_date, SUM(total) as total_per_day')
            ->get();
    }

}
