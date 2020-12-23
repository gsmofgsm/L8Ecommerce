@extends('layouts.app')

@section('title', 'Checkout')

@section('extra-css')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')

    <div class="container">

        <h1 class="checkout-heading stylish-heading">Checkout</h1>
        <div class="checkout-section">
            <div>
                <form action="{{ route('checkout.store') }}" method="POST" id="payment-form">
                    @csrf
                    <h2>Billing Details</h2>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="">
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="">
                        </div>
                        <div class="form-group">
                            <label for="province">Province</label>
                            <input type="text" class="form-control" id="province" name="province" value="">
                        </div>
                    </div> <!-- end half-form -->

                    <div class="half-form">
                        <div class="form-group">
                            <label for="postalcode">Postal Code</label>
                            <input type="text" class="form-control" id="postalcode" name="postalcode" value="">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="">
                        </div>
                    </div> <!-- end half-form -->

                    <div class="spacer"></div>

                    <h2>Payment Details</h2>

                    <div class="form-group">
                        <label for="name_on_card">Name on Card</label>
                        <input type="text" class="form-control" id="name_on_card" name="name_on_card" value="">
                    </div>

                    <div class="form-group">
                        <label for="card-element">
                            Credit or debit card
                        </label>
                        <div id="card-element">
                            <!-- a Stripe Element will be inserted here. -->
                        </div>

                        <div id="card-errors" role="alert">
                            <!-- Used to display form errors. -->
                        </div>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label for="address">Address</label>--}}
{{--                        <input type="text" class="form-control" id="address" name="address" value="">--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label for="cc-number">Credit Card Number</label>--}}
{{--                        <input type="text" class="form-control" id="cc-number" name="cc-number" value="">--}}
{{--                    </div>--}}

{{--                    <div class="half-form">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="expiry">Expiry</label>--}}
{{--                            <input type="text" class="form-control" id="expiry" name="expiry" placeholder="MM/DD">--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="cvc">CVC Code</label>--}}
{{--                            <input type="text" class="form-control" id="cvc" name="cvc" value="">--}}
{{--                        </div>--}}
{{--                    </div> <!-- end half-form -->--}}

                    <div class="spacer"></div>

                    <button id="card-button" type="submit" class="button-primary full-width">Complete Order</button>


                </form>
            </div>



            <div class="checkout-table-container">
                <h2>Your Order</h2>

                <div class="checkout-table">
{{--                    {{ dump(Cart::content()) }}--}}
                    @foreach(Cart::content() as $item)
                    <div class="checkout-table-row">
                        <div class="checkout-table-row-left">
                            <img src="{{ asset($item->model->imageUrl) }}" alt="item" class="checkout-table-img">
                            <div class="checkout-item-details">
                                <div class="checkout-table-item">{{ $item->model->name }}</div>
                                <div class="checkout-table-description">{{ $item->model->details }}</div>
                                <div class="checkout-table-price">{{ $item->model->presentPrice() }}</div>
                            </div>
                        </div> <!-- end checkout-table -->

                        <div class="checkout-table-row-right">
                            <div class="checkout-table-quantity">{{ $item->qty }}</div>
                        </div>
                    </div> <!-- end checkout-table-row -->
                    @endforeach

                </div> <!-- end checkout-table -->

                <div class="checkout-totals">
                    <div class="checkout-totals-left">
                        Subtotal <br>
{{--                        Discount (10OFF - 10%) <br>--}}
                        Tax <br>
                        <span class="checkout-totals-total">Total</span>

                    </div>

                    <div class="checkout-totals-right">
                        {{ presentPrice(Cart::subtotal()) }} <br>
{{--                        -$750.00 <br>--}}
                        {{ presentPrice(Cart::tax()) }} <br>
                        <span class="checkout-totals-total">{{ presentPrice(Cart::total()) }}</span>

                    </div>
                </div> <!-- end checkout-totals -->

            </div>

        </div> <!-- end checkout-section -->
    </div>

@endsection

@section('extra-js')
    <script>
        const stripe = Stripe('pk_test_51HxJRGJIK8V1V3nksnWNduAkGW9iy7A2fAprqCM9BpLIPIwM6dhPCNt8f94qPsBXq90VgMIiRWNVhHWmMx0vBx1Y00uO78JdIH');

        const elements = stripe.elements();
        const cardElement = elements.create('card', {
            hidePostalCode: true
        });

        cardElement.mount('#card-element');

        // Handle form submission
        var form = document.getElementById('payment-form');
        var cardHolderName = document.getElementById('name_on_card');

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card', cardElement, {
                    billing_details: { name: cardHolderName.value }
                }
            );

            if (error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                // Send the token to your server.
                console.log(paymentMethod);
                stripeTokenHandler(paymentMethod);
            }
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(paymentMethod) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'paymentMethod');
            hiddenInput.setAttribute('value', paymentMethod.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>
@endsection
