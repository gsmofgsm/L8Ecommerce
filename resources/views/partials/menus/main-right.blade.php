<ul>
    @guest()
    <li><a href="{{ route('register') }}">Sign Up</a></li>
    <li><a href="{{ route('login') }}">Login</a></li>
    @else
    <li>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li>
    @endguest
    <li>
        <a href="{{ route('cart.index') }}">
            Cart
            <span class="cart-count">
                @if(Cart::instance('default')->count())
                    <span>{{ Cart::instance('default')->count() }}</span>
                @endif
            </span>
        </a>
    </li>
</ul>
