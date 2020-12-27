<div>
    @unless (session()->has('coupon'))
        <a href="#" class="have-code">Have a Code?</a>

        <div class="have-code-container">
            <form action="{{ route('coupon.store') }}" method="POST">
                @csrf
                <input type="text" name="coupon_code" id="coupon_code">
                <button type="submit" class="button button-plain">Apply</button>
            </form>
        </div> <!-- end have-code-container -->
    @endunless
</div>
