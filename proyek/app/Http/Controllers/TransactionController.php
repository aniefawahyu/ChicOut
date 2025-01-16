<?php

namespace App\Http\Controllers;

use App\Models\Dtran;
use App\Models\Htran;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    private function checkItemStock($listCart)
    {
        $stockNotEnough = false;

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

        return $stockNotEnough;
    }

    private function calculateTotalPrice($listCart)
    {
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
        return $total;
    }

    private function createHtrans($listCart, $total, $payment = 1)
    {
        $htrans = Htran::create([
            "username" => Auth::user()->username,
            "ID_payments" => $payment,
            "total" => $total,
            "address" => Auth::user()->address
        ]);

        $ID_htrans = $htrans->ID_htrans;

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

        if ($htrans) {
            //TODO: do something
        }

        return $htrans;
    }

    private function getSnapToken($listCart, $htrans, $total)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        // $params = array(
        //     'transaction_details' => array(
        //         'order_id' => rand(),
        //         'gross_amount' => 10000,
        //     ),
        //     'customer_details' => array(
        //         'first_name' => 'budi',
        //         'last_name' => 'pratama',
        //         'email' => 'budi.pra@example.com',
        //         'phone' => '08111222333',
        //     ),
        // );

        // $snapToken = \Midtrans\Snap::getSnapToken($params);

        $products = [];
        foreach ($listCart as $item) {
            $products[] = [
                "id" => $item->Item->ID_items,
                "price" => $item->Item->price,
                "quantity" => $item->Item->qty,
                "name" => $item->Item->name
            ];
        }

        $payload = [
            "transaction_details" => [
                "order_id" => $htrans->ID_htrans,
                "gross_amount" => $total
            ],
            "customer_details" => [
                "first_name" => Auth::user()->display_name,
                "email" => Auth::user()->email,
                "phone" => Auth::user()->tel,
                "billing_adress" => [
                    "address" => Auth::user()->address,
                ]
            ],
            "item_details" => $products
            // [
            //     "id" => "a01",
            //     "price" => 7000,
            //     "quantity" => 1,
            //     "name" => "Apple"
            // ],
            // [
            //     "id" => "b02",
            //     "price" => 3000,
            //     "quantity" => 2,
            //     "name" => "Orange"
            // ]

        ];

        $snapToken = \Midtrans\Snap::getSnapToken($payload);

        return $snapToken;
    }

    public function handleTransaction(Request $req)
    {
        if ($req->has("buy")) {
            $listCart = Auth::user()->getCart;
            $stockNotEnough = $this->checkItemStock($listCart);

            if ($stockNotEnough || count($listCart) <= 0) {
                return redirect()->route('cart')->with('fail', "Some items in your cart are out of stock or not in available.");
            }

            // return redirect()->route('cart')->with('choose_payment', "");

            $total = $this->calculateTotalPrice($listCart);

            $htrans = $this->createHtrans($listCart, $total);

            $snapToken = $this->getSnapToken($listCart, $htrans, $total);

            if (!$htrans) {
                return redirect()->route('cart')->with('gagal', "Failed to complete the purchase.");
            }

            return redirect()->route('cart')->with('sukses', "Purchase successful!");
        }

        return redirect()->route('cart');
    }
}
