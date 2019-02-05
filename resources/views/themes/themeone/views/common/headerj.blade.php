<header class="main-header">
	<div class="wrapper">
		<div class="header-top">
			<ul class="top-list">
				 @if (Auth::guard('customer')->check())
				<li class="nav-item">
		            <div class="">
		                <span class="p-pic"><img src="{{getFtpImage(auth()->guard('customer')->user()->customers_picture)}}" alt="image" width="20" height="20"></span>&nbsp;@lang('website.Welcome')&nbsp;{{ auth()->guard('customer')->user()->customers_firstname }}&nbsp;{{ auth()->guard('customer')->user()->customers_lastname }}!
		            </div>
		        </li>
                <li><a href="{{ URL::to('/logout')}}">Logout</a></li>
                  @else
				<li><a href="{{ URL::to('/login')}}">Login/Register</a></li>
				@endif
				<li><a href="{{ URL::to('/contact-us')}}">Contact Us</a></li>
			</ul>
		</div>
    
			<div class="logo"><a href="{{ URL::to('/')}}"><img src="{{getFtpImage($web_setting[15]->value)}}" alt="logo"></a></div>
  		<nav class="pushmenu pushmenu-left main-nav">
  			<div class="nav-logo"><a href="index.html"><img src="{{getFtpImage($web_setting[15]->value)}}" alt="logo"></a></div>
  			<!-- <ul class="links">
  				<li><a href="{{ URL::to('/shop')}}">Shop</a></li>
  				<li><a href="#">AW18 LOOKBOOK</a></li>
  				<li><a href="#">Fit Guide</a></li>
  				<li><a href="#">Actif Club</a></li>
				<li><a href="#">Gallery</a></li>
  			</ul> -->
       
  			 <ul class="nav navbar-nav">
              <!-- <li class="active"><a href="#">Active Link</a></li>
              <li><a href="#">Link</a></li> -->
              @foreach($result['commonContent']['categories'] as $categories_data) 
                <li class="dropdown">
                	<a data-toggle="dropdown" href="{{ URL::to('/shop')}}?category={{$categories_data->categories_slug}}" >{{$categories_data->categories_description->categories_name}}</a>
              <!--     //@if(count($categories_data->sub_categories)>0) -->
				          <ul class="dropdown-menu">
                		@foreach($categories_data->sub_categories as $sub_categories_data)
                          <li class="active"><a href="{{ URL::to('/shop')}}?category={{$sub_categories_data->categories_slug}}">{{$sub_categories_data->categories_description->categories_name}}</a></li>
                    @endforeach  
                  </ul>
               <!--   // @endif  --> 
                </li>
             @endforeach
            <li class="active"><a href="{{ URL::to('/shop?type=special')}}">Top Deal</a></li>
            <li> 
             
          </ul>
  		</nav>
  		<div id="nav_list">
			<div class="bar1"></div>
			<div class="bar2"></div>
			<div class="bar3"></div>
  		</div>
  		<div class="right-icon">
  			<form class="form-inline search-form none-991" action="{{ URL::to('/shop')}}" method="get">
  				<input type="hidden" id="category_id" name="category" value="all">				 
			   <input type="search" class="search-input"  name="search" placeholder="Search..." value="{{ app('request')->input('search') }}" aria-label="Search">
			  <button type="submit" class="search-icon"><span><img src="{{asset('images/search_icon.png')}}"></span></button>
  			</form>
  			<ul>
  				<li class="photo-icon"> <a href="#"><img src="{{asset('images/photo_icon.png')}}" alt="photo icon"></a>
  					
				<!-- nologin-user-dropdown -->
				 @if (Auth::guard('customer')->check())
					<div class="nologin-user-dropdown">
		                <a href="{{ URL::to('/profile')}}">Profile</a>
		                <a href="{{ URL::to('/wishlist')}}">My Wishlist</a>
		                <a href="{{ URL::to('/orders')}}">My Orders</a>
		                 <a href="{{ URL::to('/shipping/address')}}">Shipping Address</a>
		                <a href="{{ URL::to('/logout')}}">Logout</a>
		            </div>
		            @else
		            <div class="nologin-user-dropdown">
		            <a href="{{ URL::to('/login')}}">Login/Register</a>
		            </div>
		            	@endif
  				</li>
  				                     
  				</li>
  				<li class="cart-header  head-cart-content">  				
  				 @include('cartButton')
  				   </li>
  				 <li><a href="{{ URL::to('/wishlist')}}"><img src="{{asset('images/wishlist_icon.png')}}" alt="photo icon"><span class="cart-number wishlist " id="wishlist-count">{{$result['commonContent']['totalWishList']}}</span></a></li>
  			</ul>
  		</div>
       
	</div>
</header>