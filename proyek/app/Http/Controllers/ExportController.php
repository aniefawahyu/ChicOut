<?php

namespace App\Http\Controllers;
use App\Exports\ReportExcel;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Item;
use App\Models\Cart;
use App\Models\Htran;
use App\Models\Dtran;

class ExportController extends Controller
{
    public function excelItems() {
        $listItems = Item::all();
        // Transform the data for export
        $exportData = $listItems->map(function ($item) {
            return [
                'ID_items' => $item->ID_items,
                'name' => $item->name,
                'img_url' => $item->img,
                'description' => $item->description,
                'stock' => $item->stock,
                'price' => $item->price,
                'discount' => $item->discount,
                'category' => $item->Category->name ?? 'N/A',
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'deleted_at' => $item->deleted_at,
            ];
        });
        // Define the columns for the export
        $colItem = [
            'ID_items',
            'name',
            'img_url',
            'description',
            'stock',
            'price',
            'discount',
            'category',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        // Pass both data and columns to the export class
        $exportData = new ReportExcel($exportData, $colItem);
        return Excel::download($exportData, 'List-Items.xlsx');
    }
}
