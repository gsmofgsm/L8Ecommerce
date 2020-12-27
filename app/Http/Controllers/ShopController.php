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

        if ($product->quantity > setting('site.stock_threshold')) {
            $stockLevel = '<div class="badge badge-success">In Stock</div>';
        } elseif ($product->quantity > 0) {
            $stockLevel = '<div class="badge badge-warning">Low Stock</div>';
        } else {
            $stockLevel = '<div class="badge badge-danger">Not Available</div>';
        }

        return view('product')->with([
            'product' => $product,
            'mightAlsoLike' => $mightAlsoLike,
            'stockLevel' => $stockLevel,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3'
        ]);

        $query = $request->input('query');
//        $products = Product::where('name', 'like', "%{$query}%")
//            ->orWhere('details', 'like', "%{$query}%")
//            ->orWhere('description', 'like', "%{$query}%")
//            ->simplePaginate(5); // todo: use normal paginate
        $products = Product::search($query)->simplePaginate(5); // todo: use normal paginate
        return view('search-results', compact('products'));
    }

    public function searchAlgolia(Request $request)
    {
        return view('search-results-algolia');
    }
}
