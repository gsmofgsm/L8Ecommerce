<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
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
        $newSubtotal = Cart::subtotal() - $discount;
        $newTax = $newSubtotal * $tax;
        $newTotal = $newSubtotal + $newTax;

        return collect([
            'tax' => $tax,
            'discount' => $discount,
            'newSubtotal' => $newSubtotal,
            'newTax' => $newTax,
            'newTotal' => $newTotal,
        ]);
    }
}
