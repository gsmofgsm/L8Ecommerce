<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
//use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Cart::instance('default')->count() == 0) {
            return redirect()->route('shop.index');
        }

        if (auth()->user() && request()->is('guestCheckout')) {
            return redirect()->route('checkout.index');
        }

        return view('checkout')->with([
            'discount' => $this->getNumbers()->get('discount'),
            'newSubtotal' => $this->getNumbers()->get('newSubtotal'),
            'newTax' => $this->getNumbers()->get('newTax'),
            'newTotal' => $this->getNumbers()->get('newTotal'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CheckoutRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {
//        dd($request->all());
        try {
            $contents = Cart::content()->map(function ($item) {
                return $item->model->slug . ', ' . $item->qty;
            })->values()->toJson();
//            $charge = Stripe::charges()->create([
//                'amount' => Cart::total() / 100,
//                'currency' => 'EUR',
//                'source' => $request->paymentMethod,
//                'description' => 'Order',
//                'receipt_email' => $request->email,
//                'metadata' => [
////                    'contents' => $contents,
////                    'quantity' => Cart::instance('default')->count(),
//                ],
//            ]);
            $user = new User;

            $user->charge(
                round($this->getNumbers()->get('newTotal')), $request->paymentMethod, [
                    'metadata' => [
                        'contents' => $contents,
                        'quantity' => Cart::instance('default')->count(),
                        'discount' => collect(session()->get('coupon'))->toJson(),
                    ]
                ]
            );

            // Insert into orders table
            $order = Order::create([
                'user_id' => auth()->id(),
                'billing_email' => $request->email,
                'billing_name' => $request->name,
                'billing_address' => $request->address,
                'billing_city' => $request->city,
                'billing_province' => $request->province,
                'billing_postalcode' => $request->postalcode,
                'billing_phone' => $request->phone,
                'billing_name_on_card' => $request->name_on_card,
                'billing_discount' => $this->getNumbers()->get('discount'),
                'billing_discount_code' => $this->getNumbers()->get('code'),
                'billing_subtotal' => $this->getNumbers()->get('newSubtotal'),
                'billing_tax' => $this->getNumbers()->get('newTax'),
                'billing_total' => $this->getNumbers()->get('newTotal'),
                'error' => null,
            ]);

            // Insert into order_product table
            foreach (Cart::content() as $item) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $item->model->id,
                    'quantity' => $item->qty,
                ]);
            }

            // SUCCESSFUL
            Cart::instance('default')->destroy();
            session()->forget('coupon');
//            return back()->with('success_message', 'Thank you! Your payment has been successfully accepted!');
            return redirect()->route('confirmation.index')->with('success_message', 'Thank you! Your payment has been successfully accepted!');
        } catch (\Exception $e) {
            return back()->withErrors('Erro! ' . $e->getMessage());
        }
    }

    private function getNumbers()
    {
        $tax = config('cart.tax') / 100;
        $discount = session()->get('coupon')['discount'] ?? 0;
        $code = session()->get('coupon')['name'] ?? null;
        $newSubtotal = Cart::subtotal() - $discount;
        $newTax = $newSubtotal * $tax;
        $newTotal = $newSubtotal + $newTax;

        return collect([
            'tax' => $tax,
            'discount' => $discount,
            'code' => $code,
            'newSubtotal' => $newSubtotal,
            'newTax' => $newTax,
            'newTotal' => $newTotal,
        ]);
    }
}
