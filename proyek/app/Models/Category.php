<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $table = "categories";
    protected $primaryKey = "ID_categories";
    public $incrementing = true;
    public $timestamps = true;

    public $fillable = [
        "name",
        "img",
    ];

    public function getItems() {
        return $this->hasMany(Item::class, "ID_categories", "ID_categories");
    }

    public function getItemsWithTrashed() {
        return $this->hasMany(Item::class, "ID_categories", "ID_categories")->withTrashed();
    }

    public function getTotalQtyByDateRange($startDate, $endDate)
    {
        // Use the relationship to get all related items
        $items = $this->getItemsWithTrashed;

        // Calculate the total quantity for each item category within the date range
        $totalQtyByCategory = [];

        foreach ($items as $item) {
            $totalQtyByCategory[$item->name] = $item->getDtransTotalQtyByDateRange($startDate, $endDate);
        }

        return $totalQtyByCategory;
    }
}
