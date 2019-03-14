<header class="site-header">
    <div class="topbar">
      <div class="container">
        <div class="top-left float-left">
          <ul>
            <li><a href="#"><img src="{!! asset('images/truck-icon.png') !!}" alt="truck-icon">Free Express Shipping</a></li>
          </ul>
        </div>
        <div class="top-right float-right">
          <ul>
            <li class="">
              <form class="search-home" action="{{URL::to('/shop')}}" method="post">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
                  <input type="hidden" name="category" value="all">         
                 <!--  <input type="text" class="form-control" name="x" id="search_inp" value="{{app('request')->input('search')}}" placeholder="Search for Product..."> -->

                  <input type="text" name="search" placeholder="Search Products..">
                  <button type="submit"><img src="{!! asset('images/search-icon.png') !!}"></button>
              </form>
            </li>
            <li class="border-left">
              @if (Auth::guard('customer')->check())
              <a href="{{ URL::to('/profile')}}" ><img width="50px"  src="{{getFtpImage(auth()->guard('customer')->user()->customers_picture)}}" alt="image"/> &nbsp; &nbsp; Account <i style="font-size:16px;"  class="fas fa-angle-down"></i>
              </a>
              <ul class="user-account">
                <li><a href="{{ URL::to('/profile')}}">Profile</a></li>
                <li><a href="{{ URL::to('/changepassword')}}">Change Password</a></li>
                <li><a href="{{ URL::to('/wishlist')}}">My Wishlist</a></li>
                <li><a href="{{ URL::to('/orders')}}">My Orders</a></li>
                <li><a href="{{ URL::to('/shipping/address')}}">Shipping Address</a></li>
                <li><a href="{{ URL::to('/logout')}}">Logout</a></li>
              </ul>
              @else
              <a href="{{ URL::to('/login')}}">Login/Register
              </a>
              @endif
            </li>
            <li class="border-left">
            @include('cartButton')
            </li>
          </ul>
        </div>
        <a href="{{Url::to('/')}}" class="site-logo">
          <img src="{{getFtpImage($web_setting[15]->value)}}" style="width:92px;" alt="Logo" class="default-logo d-xs-none d-xs-none-cu">
        </a>
      </div>
    </div>
    <div class="bottombar">
      <div class="container">
        <!-- start site nav -->
        <nav class="site-nav">
          <ul>
            <li class="border-left"><a href="{{Url::to('/shop')}}" title="Home">New Arrivals  </a></li>
             @foreach($result['commonContent']['categories'] as $categories_data) 
            @if( $categories_data->categories_id < 6 )
            <li class="border-left">
              <a  href="{{ URL::to('/shop')}}?category={{$categories_data->categories_slug}}" >{{$categories_data->categories_description->categories_name}}</a>
              <!--  <ul class="dropdown-menu">
                @foreach($categories_data->sub_categories as $sub_categories_data)
                      <li class="active"><a href="{{ URL::to('/shop')}}?category={{$sub_categories_data->categories_slug}}">{{$sub_categories_data->categories_description->categories_name}}</a>
                      </li>
                @endforeach  
              </ul> -->
             </li>
             @endif
            @endforeach

            <li class="border-left border-right">
              <a   title="More" > More </a> 
              <ul class="dropdown-menu">
              @foreach($result['commonContent']['categories'] as $categories_data) 
                @if( $categories_data->categories_id >= 6 )
                    <li class="active"><a href="{{ URL::to('/shop')}}?category={{$categories_data->categories_slug}}" > {{$categories_data->categories_description->categories_name}}
                      </a>
                    </li>
                @endif
              @endforeach
              </ul>
            </li>

          </ul>
        </nav>
        <nav class="site-nav site-nav-mobile">
          <div class="user">
            @if (Auth::guard('customer')->check())
            <a href="{{ URL::to('/profile')}}">
              <img src="{{getFtpImage(auth()->guard('customer')->user()->customers_picture)}}" alt="user image"></a>
            <div class="clear"></div>

            @else
            <img src="{{asset('images/photo_icon.png')}}" alt="user image">
            <div class="clear"></div>
            <ul>
              <li><a data-toggle="modal" data-target="#mobile-signin">Log In</a></li>
              <li><a data-toggle="modal" data-target="#mobile-signup">Sign Up</a></li>
            </ul>
            @endif
            <a class="close-nav"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
          </div>
          <ul>
            @if (Auth::guard('customer')->check())
            <li class="border-left border-right">
              <a> Account <i style="font-size:16px;" class="fas fa-angle-down"></i></a>
              <ul>
                <li><a href="{{ URL::to('/profile')}}">Profile</a></li>
                <li><a href="{{ URL::to('/wishlist')}}">My Wishlist</a></li>
                <li><a href="{{ URL::to('/orders')}}">My Orders</a></li>
                <li><a href="{{ URL::to('/shipping/address')}}">Shipping Address</a></li>
                <li><a href="{{ URL::to('/logout')}}">Logout</a></li>
              </ul>
            </li>
            @endif
            <li class="border-left"><a href="{{Url::to('/shop')}}" title="Home">New Arrivals  </a></li>
             @foreach($result['commonContent']['categories'] as $categories_data) 
            @if( $categories_data->categories_id < 6 )
              <li class="border-left">
                <a  href="{{ URL::to('/shop')}}?category={{$categories_data->categories_slug}}" >{{$categories_data->categories_description->categories_name}}</a>
              </li>
             @endif
            @endforeach
            <li class="border-left border-right">
              <a   > More More <i style="font-size:16px;" class="fas fa-angle-down"></i></a>
              <ul  >
              @foreach($result['commonContent']['categories'] as $categories_data) 
                @if( $categories_data->categories_id >= 6 )
                <li  >
                  <a href="{{ URL::to('/shop')}}?category={{$categories_data->categories_slug}}" > {{$categories_data->categories_description->categories_name}}
                  </a>
                </li>
                @endif
              @endforeach
              </ul>
            </li>
           
          </ul>
        </nav>
        <div id="nav-icon2">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
        <!-- end site nav -->
        <div class="search-box">
          <form action="{{URL::to('/shop')}}" method="post">
            <div class="input-group">
              <div class="input-group-btn search-panel">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  <span id="search_concept"><span class="glyphicon glyphicon-align-justify"></span> Category</span>  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  @foreach($result['commonContent']['categories'] as $categories_data) 
                  <li class="border-left">
                    <a slug="{{$categories_data->categories_slug}}" href="{{ URL::to('/shop')}}?category={{$categories_data->categories_slug}}" >{{$categories_data->categories_description->categories_name}}</a>
                      
                  </li>
                  @endforeach
                </ul>
              </div>
              <input type="hidden" name="_token" value="{{csrf_token()}}">
              <input type="hidden" name="category" value="all" id="search_param">         
              <input type="text" class="form-control" name="search"  value="{{app('request')->input('search')}}" placeholder="Search for Product...">
              <span class="input-group-btn">
                <button class="btn btn-default" id="search_btn" type="submit"><img src="{!! asset('images/search-icon.png') !!}" alt=""></button>
              </span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </header>


