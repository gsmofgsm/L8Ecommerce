@extends('layouts.app')

@section('content')
<div class="container">
    <div class="auth-pages">
        <div class="auth-left">
            <x-flash-messages />
            <h2>Returning Customer</h2>
            <div class="spacer"></div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                <input type="password" id="password" name="password" value="{{ old('password') }}" placeholder="Password" required>

                <div class="login-container">
                    <button type="submit" class="auth-button">Login</button>
                    <label class="form-check-label" for="remember">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        {{ __('Remember Me') }}
                    </label>
                </div>


                @if (Route::has('password.request'))
                    <div class="spacer"></div>
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </form>
        </div>
        <div class="auth-right">
            <h2>New Customer</h2>
            <div class="spacer"></div>
            <p><strong>Save time now.</strong></p>
            <p>You don't need an account to checkout.</p>
            <div class="spacer"></div>
            <a href="{{ route('guestCheckout.index') }}" class="auth-button auth-button--hollow">Continue as Guest</a>
            <div class="spacer"></div>&nbsp;
            <div class="spacer"></div>
            <p><strong>Save time later.</strong></p>
            <p>Create an account for fast checkout and easy access to order history.</p>
            <div class="spacer"></div>
            <a href="{{ route('register') }}" class="auth-button auth-button--hollow">Create Account</a>
        </div>
    </div>
</div>
@endsection
