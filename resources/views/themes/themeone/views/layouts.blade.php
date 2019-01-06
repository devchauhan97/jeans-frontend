<!DOCTYPE html>
<html>
@include('common.metaj')
@yield('customcss')
<body>

	<div class="clearfix "></div>
	@include('common.headerj')
	
<!-- hero slider -->
	

@yield('content')

<!-- customer-logos-slider -->
	<section class="customer-logos-slider padding-50">
		<div class="wrapper">
			<h4>Manufactures</h4>
			<div class="customer-logos slider">
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo1.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo2.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo3.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo4.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo5.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo6.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo1.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo2.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo3.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo4.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo5.png'}}"></a></div>
			    <div class="slide"><a href="#"><img src="{{asset('').'images/customerlogo6.png'}}"></a></div>
			</div>
		</div>
	</section>

<!--Footer light-grey -->

	<footer class="light-grey padding-50">
		<div class="wrapper">
			<ul class="social-icon text-center">
				<li><a href="#"><img src="{{asset('').'images/facebook_icon.png'}}"></a></li>
				<li><a href="#"><img src="{{asset('').'images/insta_icon.png'}}"></a></li>
				<li><a href="#"><img src="{{asset('').'images/youtube_icon.png'}}"></a></li>
				<li><a href="#"><img src="{{asset('').'images/twitter_icon.png'}}"></a></li>
			</ul>

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
						<li class="footer-number">000-00000-000</li>
						<li class="time">9:00 AM - 5:00 PM</li>
						<li>For Business Enquiries | <a href="#">Contact Us</a></li>
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

$(document).ready(function(){
  $('.customer-logos').slick({
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
	
</body>
</html>