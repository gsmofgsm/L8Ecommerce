<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        if (request()->category) {
//            $products = Product::with('categories')->whereHas('categories', function ($query) {
//                $query->where('slug', request()->category);
//            })->get();
//            $categoryName = $categories->where('slug', request()->category)->first()->name;
            $category = Category::where('slug', request()->category)->firstOrFail();
            $products = $category->products()->get();
            $categoryName = $category->name;
        } else {
            $products = Product::inRandomOrder()->take(12)->get();
            $categoryName = 'Featured';
        }

        return view('shop')->with([
            'products' => $products,
            'categories' => $categories,
            'categoryName' => $categoryName,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $mightAlsoLike = Product::where('slug', '!=', $slug)->mightAlsoLike()->get();

        return view('product')->with([
            'product' => $product,
            'mightAlsoLike' => $mightAlsoLike
        ]);
    }
}
