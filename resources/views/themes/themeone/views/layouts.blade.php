<!DOCTYPE html>
<html>
@include('common.metaj')
@yield('customcss')
<body>

	<div class="clearfix "></div>
	@include('common.headerj')
	<!-- hero slider -->
	@yield('content')
	<footer class="light-grey padding-50">
		<div class="wrapper">
			<ul class="social-icon text-center">
				<li><a href="{{$result['commonContent']['setting'][50]->value}}"><img src="{{asset('images/facebook_icon.png')}}"></a></li>
				<li><a href="{{$result['commonContent']['setting'][51]->value}}"><img src="{{asset('images/insta_icon.png')}}"></a></li>
				<li><a href="{{$result['commonContent']['setting'][52]->value}}"><img src="{{asset('images/twitter_icon.png')}}"></a></li>
				<li><a href="{{$result['commonContent']['setting'][53]->value}}"><img src="{{asset('images/youtube_icon.png')}}"></a></li>
			</ul>
			<div class="row">
				<div class="col-md-2 col-sm-2 pull-right">
					<!-- <form name="signup" class="form-validate" enctype="multipart/form-data" action="{{ URL::to('/contact')}}" method="post"> -->

                   
	                <!-- </form> -->
	            </div>
       		</div>
			<div class="row">
				<div class="col-md-4 col-sm-4">
					<ul>
	                <li> <a href="{{ URL::to('/')}}">@lang('website.Home')</a> </li>
	                <li> <a href="{{ URL::to('/shop')}}">@lang('website.Shop')</a> </li>
	                <li> <a href="{{ URL::to('/orders')}}">@lang('website.Orders')</a> </li>
	                <li> <a href="{{ URL::to('/viewcart')}}">@lang('website.Shopping Cart')</a> </li> 
	                <li> <a href="{{ URL::to('/wishlist')}}">@lang('website.Wishlist')</a> </li>            
	              </ul>
				</div>
				<div class="col-md-4 col-sm-4">
					<ul class="text-center">
						<li class="bold">Online Order Related Queries</li>
						<li class="footer-number">{{$result['commonContent']['setting'][11]->value}}</li>
						<li class="time"> <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group">
                        <input type="text" class="form-control field-validate" id="new_letter_email" name="email" placeholder="Enter Email">
						<span class="help-block error-content" hidden> </span>
                    </div>
                    <div class="button">
                        <button type="submit" onclick="getSubscription()" class="btn btn-dark">Subscribe Now!
                        </button>
                    </div>
                	</li>
						<li>For Business Enquiries | <a href="{{Url::to('/contact-us')}}">Contact Us</a></li>
					</ul>
				</div>
				<div class="col-md-4 col-sm-4">
					<ul >
		                @if(count($result['commonContent']['pages']))
		                    @foreach($result['commonContent']['pages'] as $page)
		                    	<li> <a href="{{ URL::to('/page?name='.$page->slug)}}">{{$page->name}}</a> </li>
		                    @endforeach
		                @endif            
	                	<li> <a href="{{ URL::to('/contact-us')}}">@lang('website.Contact Us')</a> </li>
	          		</ul>
				</div>
			</div>

			<div class="copyright text-center">
				<span>Copyright @2018</span>
			</div>
		</div>
	</footer>
  @include('common.footerj')
  @yield('customjs')
<script type="text/javascript" >
	// customer-logos slider
jQuery(document).ready(function(){
  jQuery('.customer-logos').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 1000,
    arrows: false,
    dots: false,
    pauseOnHover: false,
    responsive: [{
      breakpoint: 768,
      settings: {
        slidesToShow: 4
      }
    }, {
      breakpoint: 520,
      settings: {
        slidesToShow: 3
      }
    }]
  });
});
</script>
	@if(!empty($web_setting[77]->value))
		<?=stripslashes($web_setting[77]->value)?>
    @endif
</body>
</html>