<?php

use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;

function presentPrice($price)
{
    return money_format('$%i', $price / 100);
}

function presentDate($date)
{
    return Carbon::parse($date)->format('M d, Y');
}

function setActiveCategory($category, $output = 'active')
{
    return request()->category == $category ? $output : '';
}

function productImage($image)
{
    return $image && file_exists('storage/'.$image)
        ? asset('storage/'.$image)
        : asset('img/not-found.png');
}

function getNumbers()
{
    $tax = config('cart.tax') / 100;
    $discount = session()->get('coupon')['discount'] ?? 0;
    $code = session()->get('coupon')['name'] ?? null;
    $newSubtotal = max(Cart::subtotal() - $discount, 0);
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
