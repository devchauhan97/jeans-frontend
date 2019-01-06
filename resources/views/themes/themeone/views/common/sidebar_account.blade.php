<div class="sidebar">
    <div class="widget block-categories">
        <div class="block-title">
            <h2>@lang('website.My Account')</h2>
        </div>
        <div class="block-content">
            <ul class="list-categories">
                <li>
                    <a href="{{ URL::to('/profile')}}">@lang('website.Profile')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/wishlist')}}">@lang('website.Wishlist')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/orders')}}">@lang('website.Orders')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/shipping-address')}}">@lang('website.Shipping Address')</a>
                </li>
                <li>
                    <a href="{{ URL::to('/logout')}}">@lang('website.Logout')</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="widget block-images">
    	<ul class="list-images en">
        	<li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_1_en.jpg'}}" alt="image"></a></li>
            <li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_2_en.jpg'}}" alt="image"></a></li>
            <li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_3_en.jpg'}}" alt="image"></a></li>
        </ul>
        
        <ul class="list-images ar">
        	<li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_1_ar.jpg'}}" alt="image"></a></li>
            <li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_2_ar.jpg'}}" alt="image"></a></li>
            <li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_3_ar.jpg'}}" alt="image"></a></li>
        </ul>
    </div>
</div>