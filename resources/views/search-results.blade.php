@extends('layouts.app')

@section('title', 'Search Results')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/algolia.css') }}">
@endsection

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Search</span>
    @endcomponent

    <x-flash-messages />

    <div class="container">
        <div class="search-results-container">
            <h1>Search Results</h1>
            <p class="search-results-count">{{$products->count()}} result(s) for '{{ request()->input('query') }}'</p>

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></td>
                        <td>{{ $product->details }}</td>
                        <td>{{ Str::limit($product->description, 80) }}</td>
                        <td>{{ $product->presentPrice() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $products->appends(request()->input())->links() }}
        </div> <!-- end search-container -->
    </div>
@endsection

@section('extra-js')
    <x-algolia-autocomplete-js />
@endsection
