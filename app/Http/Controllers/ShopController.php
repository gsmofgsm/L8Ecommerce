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
        $pagination = 6;
        $categories = Category::all();
        if (request()->category) {
//            $products = Product::with('categories')->whereHas('categories', function ($query) {
//                $query->where('slug', request()->category);
//            })->get();
//            $categoryName = optional($categories->where('slug', request()->category)->first())->name;
            $category = Category::where('slug', request()->category)->firstOrFail();
            $products = $category->products();
            $categoryName = $category->name;
        } else {
            $products = Product::where('featured', true);
            $categoryName = 'Featured';
        }

        if (request()->sort == 'low_high') {
            $products = $products->orderBy('price')->simplePaginate($pagination);
        } elseif (request()->sort == 'high_low') {
            $products = $products->orderBy('price', 'desc')->simplePaginate($pagination);
        } else {
            $products = $products->simplePaginate($pagination);
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

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3'
        ]);

        $query = $request->input('query');
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('details', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->simplePaginate(5); // todo: use normal paginate
        return view('search-results', compact('products'));
    }
}
