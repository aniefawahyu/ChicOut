<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\Category;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Item;
use App\Models\Cart;
use App\Models\Htran;
use App\Models\Dtran;

use Carbon\Carbon;

use App\Charts\PieChartTopSellingCategory;
use App\Charts\LineChartTotalIncome;
use App\Models\Brand;

class MasterController extends Controller
{
    public function getMasterHome(PieChartTopSellingCategory $topSellingCategory, LineChartTotalIncome $totalIncome)
    {
        $param["period"] = "All Time";
        // piechart
        $startDate = Carbon::parse('2023-01-01');
        $endDate = Carbon::now()->endOfDay();
        $allCategoriesTotalQty = [];
        $categories = Category::withTrashed()->get();
        foreach ($categories as $category) {
            $totalQtyByCategory = $category->getTotalQtyByDateRange($startDate, $endDate);
            $allCategoriesTotalQty[] = [
                'name' => $category->name,
                'total' => $totalQtyByCategory,
            ];
        }
        $param["topSellingCategory"] = $topSellingCategory->build($allCategoriesTotalQty);

        // linechart
        $totalByDay = Htran::totalByDay($startDate, $endDate);
        $labels = $totalByDay->pluck('purchase_date')->toArray();
        $totalIncomeData = ['total' => $totalByDay->pluck('total_per_day')->toArray(), 'labels' => $labels];
        $param["totalIncome"] = $totalIncome->build($totalIncomeData);

        // htrans
        $param["htrans"] = Htran::whereBetween('purchase_date', [$startDate, $endDate])->get();


        $param["listCategory"] = Category::all();
        return view("master.home", $param);
    }

    public function postMasterHome(Request $req, PieChartTopSellingCategory $topSellingCategory, LineChartTotalIncome $totalIncome)
    {
        $param["period"] = Carbon::parse($req->startDate)->format('d F Y') . ' - ' . Carbon::parse($req->endDate)->format('d F Y');

        $validator = Validator::make($req->all(), [
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate|before_or_equal:' . now()->format('Y-m-d'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // piechart
        $startDate = $req->startDate;
        $endDate = Carbon::parse($req->endDate)->endOfDay();

        $allCategoriesTotalQty = [];
        $categories = Category::withTrashed()->get();
        foreach ($categories as $category) {
            $totalQtyByCategory = $category->getTotalQtyByDateRange($startDate, $endDate);
            $allCategoriesTotalQty[] = [
                'name' => $category->name,
                'total' => $totalQtyByCategory,
            ];
        }
        $param["topSellingCategory"] = $topSellingCategory->build($allCategoriesTotalQty);

        // linechart
        $totalByDay = Htran::totalByDay($startDate, $endDate);
        $labels = $totalByDay->pluck('purchase_date')->toArray();
        $totalIncomeData = ['total' => $totalByDay->pluck('total_per_day')->toArray(), 'labels' => $labels];
        $param["totalIncome"] = $totalIncome->build($totalIncomeData);

        // htrans
        $param["htrans"] = Htran::whereBetween('purchase_date', [$startDate, $endDate])->get();

        $param["listCategory"] = Category::all();
        return view("master.home", $param);
    }

    public function getDtrans(Request $req)
    {
        $param["listCategory"] = Category::all();
        $htrans = Htran::find($req->id);
        if ($htrans != null) {
            $param["htrans"] = $htrans;
            $param["listDtrans"] = $htrans->getDtrans;
            return view('master.trans-detail', $param);
        } else {
            return redirect()->route('master-home');
        }
    }

    public function getItems(Request $req)
    {
        if ($req->name == "All") {
            $listItem = Item::withTrashed()->paginate(10);
        } else {
            $category = Category::where('name', $req->name)->first();
            if ($category != null) {
                $listItem = $category->getItems()->withTrashed()->paginate(10);
            } else {
                return redirect()->route('master-item', ['name' => 'All']);
            }
        }
        $param["listItem"] = $listItem;
        $param["listCategory"] = Category::all();
        return view('master.item-show', $param);
    }

    public function getBrand(Request $req)
    {
        $listBrands = Brand::withTrashed()->paginate(10);

        $param["listBrandWithTrashed"] = $listBrands;
        $param["listCategory"] = Category::all();
        return view('master.brand-show', $param);
    }

    public function getBrandCRU(Request $req)
    {
        $brand = Brand::find($req->id);
        $param["listCategory"] = Category::all();
        if ($req->id == "Insert") {
            $param["brand"] = null;
            return view("master.brand-insert-update", $param);
        } else if ($brand != null) {
            $param["brand"] = $brand;
            return view("master.brand-insert-update", $param);
        } else {
            return redirect()->route('master-brand', ['name' => 'All']);
        }
    }

    public function postBrandCRU(Request $req)
    {
        // dd($req->all());
        $rules = [
            'name' => 'required|string|max:255',
            'logo' => 'required',
            'description' => 'required|string',
            'premium' => 'in:on,off',
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'logo.required' => 'The logo field is required.',
            'description.required' => 'The description field is required.',
        ];
        $validator = Validator::make($req->all(), $rules, $messages)->validate();


        $brand = Brand::find($req->id);
        if ($req->has("add")) {
            // dd($req->name);
            $res = Brand::create([
                "name" => $req->name,
                "logo" => $req->logo,
                "description" => $req->description,
                "premium" => $req->premium == "on" ? true : false,
            ]);
            return redirect()->route('master-brand', ['name' => 'All'])->with("sukses", 'Brand added successfully!');
        } else if ($req->has("save") && $brand != null) {
            $brand->update([
                "name" => $req->name,
                "logo" => $req->logo,
                "description" => $req->description,
                "premium" => $req->premium == "on" ? true : false,
            ]);
            return redirect()->route('master-brand', ['name' => 'All'])->with("sukses", 'Brand updated successfully!');
        } else {
            return redirect()->route('master-brand', ['name' => 'All']);
        }
    }

    public function deleteBrand(Request $req)
    {
        $brand = Brand::withTrashed()->find($req->id);
        if ($brand != null) {
            if ($brand->trashed()) {
                $brand->restore();
                return redirect()->route('master-brand', ['name' => 'All'])->with("sukses", 'Brand restored successfully!');
            } else {
                $brand->delete();
                return redirect()->route('master-brand', ['name' => 'All'])->with("sukses", 'Brand deleted successfully!');
            }
        } else {
            return redirect()->route('master-brand');
        }
    }

    public function getInsertUpdate(Request $req)
    {
        $item = Item::find($req->id);
        $param["listCategory"] = Category::all();
        if ($req->id == "Insert") {
            $param["item"] = null;
            return view("master.item-insert-update", $param);
        } else if ($item != null) {
            $param["item"] = $item;
            return view("master.item-insert-update", $param);
        } else {
            return redirect()->route('master-item', ['name' => 'All']);
        }
    }

    public function postInsertUpdate(Request $req)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'img' => 'required',
            'description' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'discount' => 'required|integer|min:0|max:100',
            'ID_categories' => 'required|exists:categories,ID_categories',
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'img.required' => 'The image field is required.',
            'description.required' => 'The description field is required.',
            'stock.required' => 'The stock field is required.',
            'price.required' => 'The price field is required and must be an integer.',
            'discount.required' => 'The discount field is required.',
            'discount.integer' => 'The discount field must be an integer.',
            'discount.min' => 'The discount field must be at least 0.',
            'discount.max' => 'The discount field must be at most 100.',
            'ID_categories.required' => 'The category field is required.',
            'ID_categories.exists' => 'The selected category is invalid.',
        ];
        $validator = Validator::make($req->all(), $rules, $messages)->validate();


        $item = Item::find($req->id);
        if ($req->has("add")) {
            $res = Item::create([
                "name" => $req->name,
                "img" => $req->img,
                "description" => $req->description,
                "stock" => $req->stock,
                "price" => $req->price,
                "discount" => $req->discount,
                "ID_categories" => $req->ID_categories,
            ]);
            return redirect()->route('master-item', ['name' => 'All'])->with("sukses", 'Item added successfully!');
        } else if ($req->has("save") && $item != null) {
            $item->update([
                "name" => $req->name,
                "img" => $req->img,
                "description" => $req->description,
                "stock" => $req->stock,
                "price" => $req->price,
                "discount" => $req->discount,
                "ID_categories" => $req->ID_categories,
            ]);
            return redirect()->route('master-item', ['name' => 'All'])->with("sukses", 'Item updated successfully!');
        } else {
            return redirect()->route('master-item', ['name' => 'All']);
        }
    }

    public function doDelete(Request $req)
    {
        $item = Item::withTrashed()->find($req->id);
        if ($item != null) {
            $category = $item->Category; // Retrieve the related category

            if ($item->trashed() && $category != null && $category->trashed()) {
                return redirect()->route('master-item', ['name' => 'All'])->with("fail", 'Cannot restore the item because its category is deleted. Restore the category first.');
            }
            if ($item->trashed()) {
                $item->restore();
                return redirect()->route('master-item', ['name' => 'All'])->with("sukses", 'Item restored successfully!');
            } else {
                $listCart = Cart::all();
                foreach ($listCart as $c) {
                    if ($c->ID_items == $item->ID_items) {
                        $c->delete();
                    }
                }
                $item->delete();
                return redirect()->route('master-item', ['name' => 'All'])->with("sukses", 'Item deleted successfully!');
            }
        } else {
            return redirect()->route('master-item');
        }
    }

    public function getPayment()
    {
        $param["listPayment"] = Payment::withTrashed()->get();
        $param["listCategory"] = Category::all();
        return view('master.payment-show', $param);
    }

    public function deletePayment(Request $req)
    {
        $payment = Payment::withTrashed()->find($req->id);
        if ($payment != null) {
            if ($payment->trashed()) {
                $payment->restore();
                return redirect()->route('master-payment')->with("sukses", 'Payment restored successfully!');
            } else {
                $payment->delete();
                return redirect()->route('master-payment')->with("sukses", 'Payment deleted successfully!');
            }
        } else {
            return redirect()->route('master-payment');
        }
    }

    public function getPaymentCRU(Request $req)
    {
        $payment = Payment::find($req->id);
        $param["listCategory"] = Category::all();
        if ($req->id == "Insert") {
            $param["payment"] = null;
            return view("master.payment-insert-update", $param);
        } else if ($payment != null) {
            $param["payment"] = $payment;
            return view("master.payment-insert-update", $param);
        } else {
            return redirect()->route('master-payment');
        }
    }

    public function postPaymentCRU(Request $req)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'img' => 'required',
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'img.required' => 'The image field is required.',
        ];
        $validator = Validator::make($req->all(), $rules, $messages)->validate();

        $payment = Payment::find($req->id);
        if ($req->has("add")) {
            $res = Payment::create([
                "name" => $req->name,
                "img" => $req->img,
            ]);
            return redirect()->route('master-payment')->with("sukses", 'Payment added successfully!');
        } else if ($req->has("save") && $payment != null) {
            $payment->update([
                "name" => $req->name,
                "img" => $req->img,
            ]);
            return redirect()->route('master-payment')->with("sukses", 'Payment updated successfully!');
        } else {
            return redirect()->route('master-payment');
        }
    }

    public function getCategory()
    {
        $param["listCategoryWithTrashed"] = Category::withTrashed()->get();
        $param["listCategory"] = Category::all();
        return view('master.category-show', $param);
    }

    public function deleteCategory(Request $req)
    {
        $category = Category::withTrashed()->find($req->id);
        if ($category != null && $category->ID_categories > 2) {
            if ($category->trashed()) {
                $category->restore();
                $category->getItemsWithTrashed()->restore();
                return redirect()->route('master-category')->with("sukses", 'Category restored successfully!');
            } else {
                $category->getItemsWithTrashed()->each(function ($item) {
                    $listCart = Cart::all();
                    foreach ($listCart as $c) {
                        if ($c->ID_items == $item->ID_items) {
                            $c->delete();
                        }
                    }
                    $item->delete();
                });
                $category->delete();
                return redirect()->route('master-category')->with("sukses", 'Category deleted successfully!');
            }
        } else {
            return redirect()->route('master-category');
        }
    }

    public function getCategoryCRU(Request $req)
    {
        $category = Category::find($req->id);
        $param["listCategory"] = Category::all();
        if ($req->id == "Insert") {
            $param["category"] = null;
            return view("master.category-insert-update", $param);
        } else if ($category != null) {
            $param["category"] = $category;
            return view("master.category-insert-update", $param);
        } else {
            return redirect()->route('master-category');
        }
    }

    public function postCategoryCRU(Request $req)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'img' => 'required',
        ];
        $messages = [
            'name.required' => 'The name field is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'img.required' => 'The image field is required.',
        ];
        $validator = Validator::make($req->all(), $rules, $messages)->validate();

        $category = Category::find($req->id);
        if ($req->has("add")) {
            $res = Category::create([
                "name" => $req->name,
                "img" => $req->img,
            ]);
            return redirect()->route('master-category')->with("sukses", 'Category added successfully!');
        } else if ($req->has("save") && $category != null) {
            $category->update([
                "name" => $req->name,
                "img" => $req->img,
            ]);
            return redirect()->route('master-category')->with("sukses", 'Category updated successfully!');
        } else {
            return redirect()->route('master-category');
        }
    }

    public function getAccount(Request $req)
    {
        $acc = Account::where('username', $req->search)->where('role', 'user')->first();
        if ($req->search == "Master" || $req->search == "User") {
            $param["listAccount"] = Account::where('role', $req->search)->where('username', '!=', Auth::user()->username)->get();
        } else if ($acc != null) {
            $param["acc"] = $acc;
            $param["listCategory"] = Category::all();
            $param["htrans"] = Htran::where('username', $acc->username)->get();
            return view('master.account-detail', $param);
        } else if ($req->search == "All") {
            $param["listAccount"] = Account::where('username', '!=', Auth::user()->username)->get();
        } else {
            return redirect()->route('master-account', ['search' => 'All']);
        }
        $param["listCategory"] = Category::all();
        return view('master.account-show', $param);
    }

    public function doRole(Request $req)
    {
        $account = Account::find($req->username);
        if ($account != null && $account->username != Auth::user()->username) {
            if ($account->role == "user") {
                $account->role = "master";
                $account->save();
                return redirect()->route('master-account', ['search' => 'All'])->with("sukses", 'Role updated successfully!');
            } else {
                $account->role = "user";
                $account->save();
                return redirect()->route('master-account', ['search' => 'All'])->with("sukses", 'Role updated successfully!');
            }
        } else {
            return redirect()->route('master-account', ['search' => 'All']);
        }
    }

    public function getProfile()
    {
        $param["listCategory"] = Category::all();
        return view('master.profile', $param);
    }

    public function postProfile(Request $req)
    {
        $rules = [
            'display_name' => 'required',
            'password' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value) && !password_verify($value, Auth::user()->password)) {
                    $fail("The current password is incorrect.");
                }
            }],
            'newPassword' => 'nullable|required_with:password|min:6|different:password',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $currentEmail = Auth::user()->email;

                    // Check if the email is unique or the same as the current email
                    $uniqueEmail = !Account::where('email', $value)->where('email', '!=', $currentEmail)->exists();

                    if (!$uniqueEmail) {
                        $fail("The email is already in use");
                    }
                },
            ],
            'tel' => 'required|numeric',
            'address' => 'required',
        ];

        $messages = [
            'display_name.required' => 'Display name is required.',
            'password.required_with' => 'The password is incorrect.',
            'newPassword.required_with' => 'New password is required when updating the password.',
            'newPassword.min' => 'New password must be at least 6 characters.',
            'newPassword.different' => 'New password must be different from the current password.',
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'The email is already in use.',
            'tel.required' => 'Phone number is required.',
            'tel.numeric' => 'Phone number must be numeric.',
            'address.required' => 'Address is required.',
        ];

        $validator = Validator::make($req->all(), $rules, $messages)->validate();

        $acc = Account::where('username', Auth::user()->username)->first();
        if ($req->newPassword != null) {
            $acc->update([
                "display_name" => $req->display_name,
                "password" => $req->newPassword,
                "email" => $req->email,
                "tel" => $req->tel,
                "address" => $req->address,
            ]);

            return redirect()->route('master-profile')->with('sukses', 'Account updated successfully!');
        } else {
            $acc->update([
                "display_name" => $req->display_name,
                "email" => $req->email,
                "tel" => $req->tel,
                "address" => $req->address,
            ]);
            return redirect()->route('master-profile')->with('sukses', 'Account updated successfully!');
        }
    }
}
