<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\Account;
use App\Models\Item;
use App\Models\Review;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Htran;
use App\Models\Dtran;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function getHomePage()
    {
        $topItems = Dtran::select('ID_items', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('ID_items')
            ->orderByDesc('total_qty')
            ->take(8)
            ->get();
        $topItemIDs = $topItems->pluck('ID_items')->toArray();
        $bestSeller = Item::whereIn('ID_items', $topItemIDs)->get();

        $listMerch = Category::whereNotIn('name', ['Men', 'Women', 'Kids'])->get();
        $listItem = Item::all();

        $param["bestSeller"] = $bestSeller;
        $param["listItem"] = $listItem;
        $param["listMerch"] = $listMerch;
        return view("user.home", $param);
    }

    public function getCategoryPage(Request $req)
    {
        $categoryName = $req->nama;
        // dd($categoryName);
        $category = Category::where('name', $categoryName)->first();
        // dd($category);
        if ($category) {
            $listItem = $category->getItems;
            $listMerch = Category::whereNotIn('name', ['Men', 'Women', 'Kids'])->get();
            $param["listItem"] = $listItem;
            $param["listMerch"] = $listMerch;
            $param["selectedCategory"] = $category;

            $categoryQuery = $categoryName == 'Men' ? 'mens-shirts' : 'womens-dresses';
            $response = Http::get('https://dummyjson.com/products/category/' . $categoryQuery);

            if (!$response->ok()) {
                return redirect()->route('category');
            }

            $currencyResponse = Http::get('https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.json');

            if (!$currencyResponse->ok()) {
                return redirect()->route('category');
            }

            $idrMultiplier = $currencyResponse->json()['usd']['idr'];

            foreach ($response->json()['products'] as $product) {
                $item = new Item();
                $item->ID_items = $product['id'];
                $item->name = $product['title'];
                $item->img = $product['images'][0];
                $item->description = $product['description'];
                $item->stock = $product['stock'];
                $item->price = $product['price'] * $idrMultiplier;
                $item->discount = $product['discountPercentage'];
                $item->ID_categories = $category['ID_categories'];
                $param['listCollaborationItem'][] = $item;
            }

            return view("user.category", $param);
        } else {
            return redirect()->route('category');
        }
    }


    public function getDetailPage(Request $req)
    {
        $selectedItem = Item::where('ID_items', $req->id)->withTrashed()->first();
        if ($selectedItem) {
            $listMerch = Category::whereNotIn('name', ['Food', 'Drink'])->get();
            $itemReviews = $selectedItem->getReviews;
            $averageRate = $itemReviews->avg('rating');

            if (Auth::check()) {
                $buyed = Dtran::where('ID_items', $req->id)
                    ->whereHas('Htrans', function ($query) {
                        $query->where('username', Auth::user()->username);
                    })
                    ->exists();
                $param["buyed"] = $buyed;
            }
            $param["averageRate"] = $averageRate ?: 0;
            $param["itemReviews"] = $itemReviews;
            $param["selectedItem"] = $selectedItem;
            $param["listMerch"] = $listMerch;

            return view("user.detail", $param);
        } else {
            return redirect()->route('home');
        }
    }

    public function postDetailPage(Request $req)
    {
        $selectedItem = Item::where('id_items', $req->id)->first();
        if ($req->has("addCart")) {
            $listCart = Auth::user()->getCart;
            if ($listCart->contains('ID_items', $req->id)) {
                $existingCartItem = $listCart->where('ID_items', $req->id)->first();

                // Calculate available stock
                $availableStock = $selectedItem->stock - ($existingCartItem->qty + $req->qty);
                if ($availableStock >= 0) {
                    // Update the quantity in the cart
                    $existingCartItem->update([
                        'qty' => $existingCartItem->qty + $req->qty
                    ]);
                    return redirect()->route('cart');
                } else {
                    // Return to detail page with a message
                    return redirect()->route('detail', ['id' => $req->id])->with("pesan", "Cannot add more than available stock!");
                }
            } else {
                if ($selectedItem->stock > 0 && $req->qty <= $selectedItem->stock && $req->qty > 0) {
                    Cart::create([
                        "username" => Auth::user()->username,
                        "ID_items" => $req->id,
                        "qty" => $req->qty
                    ]);
                    return redirect()->route('cart');
                } else {
                    return redirect()->route('detail', ['id' => $req->id])->with("pesan", "Qty tidak boleh 0 dan tidak boleh lebih dari" . $selectedItem['stock'] . "!");
                }
            }
        } else if ($req->has("postComment")) {
            $req->validate([
                'rating' => [
                    'required',
                    Rule::in(['1', '2', '3', '4', '5']),
                ]
            ], [
                'rating.required' => 'Please select a rating.',
            ]);

            $rating = $req->input('rating');
            $comment = $req->input('comment');
            Review::create([
                "username" => Auth::user()->username,
                "ID_items" => $req->id,
                "rating" => $rating,
                "comment" => $comment,
            ]);
            return redirect()->back()->with('sukses', 'Comment posted successfully!');
        } else if ($req->has("delete_comment")) {
            Review::find($req->delete_comment)->delete();
            return redirect()->back()->with('sukses', 'Comment deleted successfully!');
        } else {
            return redirect()->back();
        }
    }

    public function getCollaborationDetailPage(Request $req)
    {
        $itemResponse = Http::get('https://dummyjson.com/products/' . $req->id);

        if (!$itemResponse->ok()) {
            return redirect()->route('home');
        }

        $product = $itemResponse->json();

        $currencyResponse = Http::get('https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.json');

        if (!$currencyResponse->ok()) {
            return redirect()->route('home');
        }

        $idrMultiplier = $currencyResponse->json()['usd']['idr'];

        $selectedItem = new Item();
        $selectedItem->ID_items = $product['id'];
        $selectedItem->name = $product['title'];
        $selectedItem->img = $product['images'][0];
        $selectedItem->description = $product['description'];
        $selectedItem->stock = $product['stock'];
        $selectedItem->price = $product['price'] * $idrMultiplier;
        $selectedItem->discount = $product['discountPercentage'];
        $selectedItem->ID_categories = $req->categoryId;

        if ($product) {
            $listMerch = Category::whereNotIn('name', ['Food', 'Drink'])->get();
            $itemReviews = [];
            $averageRate = $product['rating'];

            if (Auth::check()) {
                $buyed = Dtran::where('ID_items', $req->id)
                    ->whereHas('Htrans', function ($query) {
                        $query->where('username', Auth::user()->username);
                    })
                    ->exists();
                $param["buyed"] = $buyed;
            }
            $param["averageRate"] = $averageRate ?: 0;
            $param["itemReviews"] = $itemReviews;
            $param["selectedItem"] = $selectedItem;
            $param["listMerch"] = $listMerch;

            return view("user.detail", $param);
        } else {
            return redirect()->route('home');
        }
    }

    public function getCartPage()
    {
        if (Auth::user()->role == "master") {
            return redirect()->route('master-home');
        }
        $listCart = Auth::user()->getCart;
        $listPayment = Payment::all();
        $listMerch = Category::whereNotIn('name', ['Food', 'Drink'])->get();

        $param["listMerch"] = $listMerch;
        $param["listPayment"] = $listPayment;
        $param["listCart"] = $listCart;
        return view("user.cart", $param);
    }

    public function postCartPage(Request $req)
    {
        // dd(Auth::user());
        if ($req->has("clear")) {
            Auth::user()->getCartPage->delete();
        } else if ($req->has("delete")) {
            $cartItem = Cart::find($req->delete);
            if ($cartItem) {
                $cartItem->delete();
            }
        }

        return redirect()->route('cart');
    }

    public function updateCart(Request $request)
    {
        $listMerch = Category::whereNotIn('name', ['Food', 'Drink'])->get();
        // Validate the request
        $request->validate([
            'ID_cart' => 'required',
            'qty' => 'required|numeric',
        ]);

        $cartItem = Cart::find($request->ID_cart);
        if ($request->qty <= 0) {
            $cartItem->qty = 1;
        } else if ($request->qty > $cartItem->Item->stock) {
            $cartItem->qty = $cartItem->Item->stock;
        } else {
            $cartItem->qty = $request->qty;
        }
        $cartItem->save();

        $listCart = Auth::user()->getCart;
        $view = view('user.partial-cart', compact('listCart'))->render();
        return response()->json(['view' => $view]);
    }

    public function getProfilePage()
    {
        if (Auth::user()->role == "master") {
            return redirect()->route('master-home');
        }
        // $listMerch = Category::whereNotIn('name', ['Food', 'Drink'])->get();
        // $param["listMerch"] = $listMerch;
        $param["myReviews"] = Auth::user()->myReviews;
        $param["listHtrans"] = Auth::user()->getHtrans;
        return view("user.profile", $param);
    }

    public function postProfilePage(Request $req)
    {
        $listMerch = Category::whereNotIn('name', ['Food', 'Drink'])->get();
        $param["listMerch"] = $listMerch;
        $param["myReviews"] = Auth::user()->myReviews;

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

            return redirect()->route('profile')->with('sukses', 'Account updated successfully!');
        } else {
            $acc->update([
                "display_name" => $req->display_name,
                "email" => $req->email,
                "tel" => $req->tel,
                "address" => $req->address,
            ]);
            return redirect()->route('profile')->with('sukses', 'Account updated successfully!');
        }
    }

    public function getDtrans(Request $req)
    {
        if (Auth::user()->role == "master") {
            return redirect()->route('master-home');
        }
        $listMerch = Category::whereNotIn('name', ['Food', 'Drink'])->get();
        $param["listMerch"] = $listMerch;

        $htrans = Htran::with(['Payment' => function ($query) {
            $query->withTrashed();
        }])->find($req->id);
        if ($htrans) {
            $param["listDtrans"] = $htrans->getDtrans;
            $param["htrans"] = $htrans;
            return view("user.history", $param);
        } else {
            return redirect()->route('profile');
        }
    }
}
