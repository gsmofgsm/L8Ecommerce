@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/algolia.css') }}">
@endsection

@section('content')

    @component('components.breadcrumbs')
            <a href="#">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>Shopping Cart</span>
    @endcomponent

    <x-flash-messages />

    <div class="cart-section container">
        <div>
            @if (Cart::count() > 0)

            <h2>{{ Cart::count() }} items in Shopping Cart</h2>

            <div class="cart-table">
                @foreach(Cart::content() as $item)
                <div class="cart-table-row">
                    <div class="cart-table-row-left">
                        <a href="{{ route('shop.show', $item->model->slug) }}">
                            <img src="{{ asset('storage/'.$item->model->image) }}" alt="item" class="cart-table-img">
                        </a>
                        <div class="cart-item-details">
                            <div class="cart-table-item">
                                <a href="{{ route('shop.show', $item->model->slug) }}">
                                    {{ $item->model->name }}
                                </a>
                            </div>
                            <div class="cart-table-description">{{ $item->model->details }}</div>
                        </div>
                    </div>
                    <div class="cart-table-row-right">
                        <div class="cart-table-actions">
{{--                            <a href="#">Remove</a> <br>--}}
                            <form action="{{ route('cart.destroy', $item->rowId) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cart-options">Remove</button>
                            </form>
{{--                            <a href="#">Save for Later</a>--}}
                            <form action="{{ route('cart.later', $item->rowId) }}" method="POST">
                                @csrf
                                <button type="submit" class="cart-options">Save for Later</button>
                            </form>
                        </div>
                        <div>
                            <select class="quantity" data-id="{{ $item->rowId }}">
                                @for($i = 1; $i <= 5; $i++)
                                <option {{ $item->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>{{ presentPrice($item->subtotal) }}</div>
                    </div>
                </div> <!-- end cart-table-row -->
                @endforeach
            </div> <!-- end cart-table -->

            <div class="cart-totals">
                <div class="cart-totals-left">
                    Shipping is free because we’re awesome like that. Also because that’s additional stuff I don’t feel like figuring out :).
                </div>

                <div class="cart-totals-right">
                    <div>
                        Subtotal <br>
                        Tax(21%) <br>
                        <span class="cart-totals-total">Total</span>
                    </div>
                    <div class="cart-totals-subtotal">
                        {{ presentPrice(Cart::subtotal()) }} <br>
                        {{ presentPrice(Cart::tax()) }} <br>
                        <span class="cart-totals-total">{{ presentPrice(Cart::total()) }}</span>
                    </div>
                </div>
            </div> <!-- end cart-totals -->

            <div class="cart-buttons">
                <a href="{{ route('shop.index') }}" class="button">Continue Shopping</a>
                <a href="{{ route('checkout.index') }}" class="button-primary">Proceed to Checkout</a>
            </div>

            @else
                <h3>No items in Cart!</h3>
                <div class="spacer"></div>
                <a href="{{ route('shop.index') }}" class="button">Continue Shopping</a>
                <div class="spacer"></div>
            @endif

            @if (Cart::instance('saveForLater')->count() > 0)
            <h2>{{ Cart::instance('saveForLater')->count() }} items Saved For Later</h2>

            <div class="saved-for-later cart-table">
                @foreach(Cart::instance('saveForLater')->content() as $item)
                <div class="cart-table-row">
                    <div class="cart-table-row-left">
                        <a href="{{ route('shop.show', $item->model->slug) }}">
                            <img src="{{ asset('storage/'.$item->model->image) }}" alt="item" class="cart-table-img">
                        </a>
                        <div class="cart-item-details">
                            <div class="cart-table-item"><a href="{{ route('shop.show', $item->model->slug) }}">
                                    {{ $item->model->name }}</a></div>
                            <div class="cart-table-description">{{ $item->model->details }}</div>
                        </div>
                    </div>
                    <div class="cart-table-row-right">
                        <div class="cart-table-actions">
{{--                            <a href="#">Remove</a> <br>--}}
{{--                            <a href="#">Move to cart</a>--}}
                            <form action="{{ route('later.destroy', $item->rowId) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cart-options">Remove</button>
                            </form>
                            <form action="{{ route('later.cart', $item->rowId) }}" method="POST">
                                @csrf
                                <button type="submit" class="cart-options">Move to cart</button>
                            </form>
                        </div>
                        {{-- <div>
                            <select class="quantity">
                                <option selected="">1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div> --}}
                        <div>{{ $item->model->presentPrice() }}</div>
                    </div>
                </div> <!-- end cart-table-row -->
                @endforeach
            </div> <!-- end saved-for-later -->

            @else
                <h3>You have no items saved for later.</h3>
            @endif

        </div>

    </div> <!-- end cart-section -->

    @include('partials.might-like')


@endsection

@section('extra-js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        (function() {
            const classname = document.querySelectorAll('.quantity');

            Array.from(classname).forEach(function (element) {
                element.addEventListener('change', function() {
                    const id = element.getAttribute('data-id');
                    axios.patch(`/cart/${id}`, {
                        quantity: this.value,
                    })
                        .then(function (response) {
                            // console.log(response);
                            window.location.href = '{{ route('cart.index') }}';
                        })
                        .catch(function (error) {
                            // console.log(error);
                            window.location.href = '{{ route('cart.index') }}';
                        });
                })
            })
        })();
    </script>
@endsection

@section('extra-js')
    <x-algolia-autocomplete-js />
@endsection
