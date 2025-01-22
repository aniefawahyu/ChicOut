<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $table = "items";
    protected $primaryKey = "ID_items";
    public $incrementing = true;
    public $timestamps = true;

    public $fillable = [
        "name",
        "img",
        "description",
        "stock",
        "price",
        "discount",
        "ID_categories",
        "collaboration_id"
    ];

    public function getReviews()
    {
        return $this->hasMany(Review::class, "ID_items", "ID_items");
    }

    public function Category()
    {
        return $this->belongsTo(Category::class, "ID_categories", "ID_categories")->withTrashed();
    }

    public function dtrans()
    {
        return $this->hasMany(Dtran::class, "ID_items", "ID_items");
    }

    public function getDtransTotalQtyByDateRange($startDate, $endDate)
    {
        // Check if the Dtran relationship is not null
        if ($this->dtrans) {
            // Use the relationship to get all related dtrans with htrans in the specified date range
            $dtrans = $this->dtrans()->whereHas('Htrans', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('purchase_date', [$startDate, $endDate]);
            });

            // Sum the quantity for each dtrans
            $totalQty = $dtrans->sum('qty');

            return $totalQty;
        }

        // Return a default value (e.g., 0) if the Dtran relationship is null
        return 0;
    }
}
