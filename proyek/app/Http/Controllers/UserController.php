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
use App\Models\Retur;

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

        $listMerch = Category::whereNotIn('name', ['Men', 'Women'])->get();
        $listItem = Item::all();

        $param["bestSeller"] = $bestSeller;
        $param["listItem"] = $listItem;
        $param["listMerch"] = $listMerch;
        return view("user.home", $param);
    }

    public function getCategoryPage(Request $req)
    {
        $categoryName = $req->nama;
        $category = Category::where('name', $categoryName)->first();

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
        // Validate input parameters
        // $req->validate([
        //     'id' => 'required|numeric',
        //     'categoryId' => 'required|numeric',
        // ]);

        // Fetch product details from the external API
        $itemResponse = Http::get('https://dummyjson.com/products/' . $req->id);

        if (!$itemResponse->ok()) {
            return redirect()->route('home')->withErrors('Failed to fetch product details. Please try again later.');
        }

        $product = $itemResponse->json();

        // Fetch currency exchange rates
        $currencyResponse = Http::get('https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.json');

        if (!$currencyResponse->ok()) {
            return redirect()->route('home')->withErrors('Failed to fetch currency conversion rates. Please try again later.');
        }

        // Convert price to IDR
        $idrMultiplier = $currencyResponse->json()['usd']['idr'];

        // Map product data to the Item model
        $selectedItem = new Item();
        $selectedItem->ID_items = $product['id'];
        $selectedItem->name = $product['title'];
        $selectedItem->img = $product['images'][0];
        $selectedItem->description = $product['description'];
        $selectedItem->stock = $product['stock'];
        $selectedItem->price = $product['price'] * $idrMultiplier;
        $selectedItem->discount = $product['discountPercentage'];
        $selectedItem->ID_categories = $req->categoryId;

        // Prepare additional data for the view
        $listMerch = Category::whereNotIn('name', ['Food', 'Drink'])->get();
        $averageRate = $product['rating'] ?? 0;

        $param = [
            'selectedItem' => $selectedItem,
            'listMerch' => $listMerch,
            'averageRate' => $averageRate,
            'itemReviews' => [], // Reviews not available for dummy API products
        ];

        if (Auth::check()) {
            $buyed = Dtran::where('ID_items', $req->id)
                ->whereHas('Htrans', function ($query) {
                    $query->where('username', Auth::user()->username);
                })
                ->exists();
            $param['buyed'] = $buyed;
        }

        return view('user.detail', $param);
    }

    public function postCollaborationDetailPage(Request $req)
    {
        $collaborationItem = Item::where('collaboration_id', $req->id)->first();

        if (!$collaborationItem) {
            // Fetch product details from the external API
            $itemResponse = Http::get('https://dummyjson.com/products/' . $req->id);

            if (!$itemResponse->ok()) {
                return redirect()->route('home')->withErrors('Failed to fetch product details. Please try again later.');
            }

            $product = $itemResponse->json();

            // Fetch currency exchange rates
            $currencyResponse = Http::get('https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.json');

            if (!$currencyResponse->ok()) {
                return redirect()->route('home')->withErrors('Failed to fetch currency conversion rates. Please try again later.');
            }

            // Convert price to IDR
            $idrMultiplier = $currencyResponse->json()['usd']['idr'];

            // Map product data to the Item model
            $collaborationItem = new Item();
            $collaborationItem->name = $product['title'];
            $collaborationItem->img = $product['images'][0];
            $collaborationItem->description = $product['description'];
            $collaborationItem->stock = $product['stock'];
            $collaborationItem->price = $product['price'] * $idrMultiplier;
            $collaborationItem->discount = $product['discountPercentage'];
            $collaborationItem->ID_categories = $req->categoryId;
            $collaborationItem->collaboration_id = $req->id;
            $collaborationItem->save();
        }

        $collaborationItem = Item::where('collaboration_id', $req->id)->first();
        // dd($collaborationItem);
        if ($req->has("addCart")) {
            $listCart = Auth::user()->getCart;
            if ($listCart->contains('ID_items', $collaborationItem->ID_items)) {
                $existingCartItem = $listCart->where('ID_items', $collaborationItem->ID_items)->first();

                // Calculate available stock
                $availableStock = $collaborationItem->stock - ($existingCartItem->qty + $req->qty);
                if ($availableStock >= 0) {
                    // Update the quantity in the cart
                    $existingCartItem->update([
                        'qty' => $existingCartItem->qty + $req->qty
                    ]);
                    return redirect()->route('cart');
                } else {
                    // Return to detail page with a message
                    return redirect()->route('collaboration-detail', ['id' => $req->id, 'categoryId' => $req->categoryId])->with("pesan", "Cannot add more than available stock!");
                }
            } else {
                if ($collaborationItem->stock > 0 && $req->qty <= $collaborationItem->stock && $req->qty > 0) {
                    Cart::create([
                        "username" => Auth::user()->username,
                        "ID_items" => $collaborationItem->ID_items,
                        "qty" => $req->qty
                    ]);
                    return redirect()->route('cart');
                } else {
                    return redirect()->route('detail', ['id' => $req->id, 'categoryId' => $req->categoryId])->with("pesan", "Qty tidak boleh 0 dan tidak boleh lebih dari" . $collaborationItem['stock'] . "!");
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
                "ID_items" => $collaborationItem->ID_items,
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


    public function getCartPage()
    {
        if (Auth::user()->role == "master") {
            return redirect()->route('master-home');
        }
        $listCart = Auth::user()->getCart;
        $listPayment = Payment::all();
        $listMerch = Category::whereNotIn('name', ['Men', 'Women', 'Kids'])->get();

        $param["listMerch"] = $listMerch;
        $param["listPayment"] = $listPayment;
        $param["listCart"] = $listCart;
        return view("user.cart", $param);
    }

    public function postCartPage(Request $req)
    {
        if ($req->has("clear")) {
            Auth::user()->getCart()->delete();
        } else if ($req->has("delete")) {
            $cartItem = Cart::find($req->delete);
            if ($cartItem) {
                $cartItem->delete();
            }
        } else if ($req->has("buy")) {
            $stockNotEnough = false;
            $listCart = Auth::user()->getCart;
            foreach ($listCart as $item) {
                if ($item->Item->stock - $item->qty < 0) {
                    $stockNotEnough = true;
                    if ($item->Item->stock != 0) {
                        $item->update(["qty" => $item->Item->stock]);
                    } else {
                        $item->delete();
                    }
                }
            }
            if ($stockNotEnough || count($listCart) <= 0) {
                return redirect()->route('cart')->with('fail', "Some items in your cart are out of stock or not in available.");
            }
            return redirect()->route('cart')->with('choose_payment', "");
        } else if ($req->has("pay")) {
            $listCart = Auth::user()->getCart;
            $total = 0;
            foreach ($listCart as $item) {
                if ($item->Item->discount != 0) {
                    $subtotal = floor($item->Item->price - ($item->Item->price * $item->Item->discount / 100)) * $item->qty;
                    $total += $subtotal;
                } else {
                    $subtotal = $item->Item->price * $item->qty;
                    $total += $subtotal;
                }
            }
            $htrans = Htran::create([
                "username" => Auth::user()->username,
                "ID_payments" => $req->pay,
                "total" => $total,
                "address" => Auth::user()->address
            ]);
            $ID_htrans = $htrans->ID_htrans;
            if ($htrans) {
                foreach ($listCart as $item) {
                    $subtotal = 0;
                    if ($item->Item->discount != 0) {
                        $subtotal = floor($item->Item->price - ($item->Item->price * $item->Item->discount / 100)) * $item->qty;
                    } else {
                        $subtotal = $item->Item->price * $item->qty;
                    }
                    Dtran::create([
                        "ID_htrans" => $ID_htrans,
                        "ID_items" => $item->Item->ID_items,
                        "qty" => $item->qty,
                        "price" => $item->Item->price,
                        "discount" => $item->Item->discount,
                        "subtotal" => $subtotal
                    ]);
                    $product = Item::where('ID_items', $item->Item->ID_items)->first();
                    $product->update(["stock" => $product->stock - $item->qty]);
                }
                Auth::user()->getCart()->delete();
                return redirect()->route('cart')->with('sukses', "Purchase successful!");
            } else {
                return redirect()->route('cart')->with('gagal', "Failed to complete the purchase.");
            }
        }

        return redirect()->route('cart');
    }

    public function updateCart(Request $request)
    {
        $listMerch = Category::whereNotIn('name', ['Men', 'Women', 'Kids'])->get();
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
        // $listMerch = Category::whereNotIn('name', ['Men', 'Women'])->get();
        // $param["listMerch"] = $listMerch;
        $param["myReviews"] = Auth::user()->myReviews;
        $param["listHtrans"] = Auth::user()->getHtrans;
        return view("user.profile", $param);
    }

    public function postProfilePage(Request $req)
    {
        $listMerch = Category::whereNotIn('name', ['Men', 'Women'])->get();
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
        $listMerch = Category::whereNotIn('name', ['Men', 'Women'])->get();
        $param["listMerch"] = $listMerch;

        $htrans = Htran::with(['Payment' => function ($query) {
            $query->withTrashed();
        }])->find($req->id);
        if ($htrans) {
            $param["listDtrans"] = $htrans->getDtrans;
            // dd($param["listDtrans"]);
            $param["htrans"] = $htrans;
            return view("user.history", $param);
        } else {
            return redirect()->route('profile');
        }
    }

    public function showReturnForm(Request $req, $id)
    {
        $dtrans = Dtran::with('Item')->find($id);

        if (!$dtrans) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json([
            'id' => $dtrans->ID_dtrans,
            'name' => $dtrans->Item->name,
            'qty' => $dtrans->qty,
            'reason' => '', // Placeholder untuk alasan
        ]);
    }

    public function processReturn(Request $req)
    {
        $validated = $req->validate([
            'id' => 'required|exists:dtrans,id',
            'reason' => 'required|string|max:255',
        ]);

        $retur = Retur::create([
            'id_pesanan' => $validated['id'],
            'qty' => Dtran::find($validated['id'])->qty,
            'alasan' => $validated['reason'],
            'processed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Return processed successfully!');
    }
}
