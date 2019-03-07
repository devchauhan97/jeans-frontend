<!DOCTYPE html>
<html>
@include('common.metaj')
@yield('customcss')
<body> 
<!-- Google Tag Manager (noscript) -->
<!-- <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-55ZQ3ZP"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> -->
<!-- End Google Tag Manager (noscript) -->


	@include('common.headerj')
	<!-- hero slider -->
	@yield('content')
 	

	<!-- <footer class="light-grey padding-50">
		<div class="wrapper">
			<ul class="social-icon text-center">
				<li><a href="{{$result['commonContent']['setting'][50]->value}}"><img src="{{asset('images/facebook_icon.png')}}"></a></li>
				<li><a href="{{$result['commonContent']['setting'][51]->value}}"><img src="{{asset('images/insta_icon.png')}}"></a></li>
				<li><a href="{{$result['commonContent']['setting'][52]->value}}"><img src="{{asset('images/twitter_icon.png')}}"></a></li>
				<li><a href="{{$result['commonContent']['setting'][53]->value}}"><img src="{{asset('images/youtube_icon.png')}}"></a></li>
			</ul>
			<div class="row">
				<div class="col-md-2 col-sm-2 pull-right">
					 
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
	</footer> -->
<div id="message_content"></div>
<div class="loader" id="loader">
 	<img src="{{asset('images/loader.gif')}}">
</div>
<!-- Mobile view login -->
<div class="modal" id="mobile-login">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title"><a href="{{URL::to('/')}}" ><img style="width: 80px;" src="{{getFtpImage($web_setting[15]->value)}}"></a></h4>
			   	
			  <button type="button" class="close"  id="login_pop_up_close"><i style="color:#fff;" class="fa fa-times" aria-hidden="true"></i></button>
			   
			  <div class="clear"></div>
			</div>
			<span>Please enter your Email to Login/Sing Up before you place the order</span>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="login-form">
					@if(Session::has('loginError'))
			        <div class="alert alert-danger alert-dismissible fade show" role="alert">
			            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			            <span class="sr-only">@lang('website.Error'):</span>
			            {!! session('loginError') !!}
			            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            	<span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        @endif
			       
					<form  class="sign-in form-validate"  id="sign_in" enctype="multipart/form-data"  action="{{ URL::to('/customer/login')}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
						<label class="mail">
							<input type="email" placeholder="Your Email"  name="email" id="email" class="email-validate"  value="{{old('email')}}" >
							<spam class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</spam>
							<small class="text-danger">{{ $errors->first('email') }}</small>
						</label>

						<label class="pass">
							<input type="password" placeholder="Your Password" name="password" id="password" class="field-validate"  value="{{old('password')}}" >
							<spam class="help-block error-content" hidden>@lang('website.Please enter your password')</spam>
							<small class="text-danger">{{ $errors->first('password') }}</small>
						</label>
						<spam class="help-block error-content"  id="sign_in_form_error" hidden></spam>
						<label class="letter-box"><a onclick="login('sign_in')" class="btn btn-primary btn-dark btn-block">Login</a></label>
						@if( $result['commonContent']['setting'][2]->value == 1)
						<label class="face"><a href="{{URL::to('login/facebook')}}">Facebook</a></label>
						@endif
						@if( $result['commonContent']['setting'][61]->value == 1 )
						<label class="googlebtn"><a href="{{URL::to('login/google')}}">Google +</a></label>
						@endif
					</form>
				</div>
			</div>
			<!-- Modal footer -->
			<a style="" class="forgot" href="{{Url::to('forgot/password')}}">Forgot Password?</a>
			<div class="clear"></div>
			<div class="forgot2">
				<a style="font-weight:500;"  >Not A Member?</a> 
				<a  >Signup</a>
			</div>
		</div>
	</div>
</div>
@include('common.footerj')
@yield('customjs')
<script type="text/javascript" >
	// customer-logos slider
/*jQuery(document).ready(function(){
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
});*/
jQuery(document).ready(function(){

	jQuery("#nav-icon2").click(function(){
		jQuery(".site-nav-mobile").css("display", "block").show('500');
	});

	jQuery(".close-nav").click(function(){
		jQuery(".site-nav-mobile").css("display", "none").show('500');
	});

	jQuery('#nav-icon1,#nav-icon2,#nav-icon3,#nav-icon4').click(function(){
		jQuery(this).toggleClass('open');
	});

	jQuery(".search-btn").click(function(){
		jQuery(".search-box").toggle();
	});
	 
	jQuery('.search-panel .dropdown-menu').find('a').click(function(e) {
		e.preventDefault();
		var param = jQuery(this).attr("href").replace("#","");
		var concept = jQuery(this).html();
		jQuery('.search-panel span#search_concept').html(concept);
		jQuery('#search_param').val(jQuery(this).attr("slug"))
	});

	// jQuery("#search_btn ,#search_btn").click(function(){
	// 	var inp= jQuery('#search_inp').val();
	// 	var cat= jQuery('#cat_inp').val();
	// 	var url ='{{Url::to("/shop?category=")}}'+jQuery('#search_param').val()
	// 	window.location.href=url+'&search='+inp
	// });

	// jQuery("#search_inp").keydown(function (e) {
	//   if (e.keyCode == 13) {
	//     jQuery('#search_btn').click();
	//   }
	// });
	jQuery(".search-home button").click(function(){
		jQuery(".search-home input").addClass("input-focus");
	});

	var isMobile = navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 || navigator.userAgent.match(/Windows Phone/i) 
	 || (window.innerWidth <= 800 && window.innerHeight <= 600)
	 ? true : false;

	var page = false;

 	@if(Request::path() == 'login')
 		page = true;
	@endif

	if( page &&  isMobile ) {

		console.log('mobile view')
		jQuery('#mobile-login').modal({ show: true });
		jQuery('.login-page').hide();

	} else {

		 console.log('destop view')

	}

	jQuery('#login_pop_up_close').click(function(){
		console.log('close login')
		jQuery('#mobile-login').modal('hide');

		if( page && isMobile ) {

			window.location = '{{URL::to('/')}}'

		}

	});
	// 
	jQuery('.sign-in').bind('keypress','input', function( event ) {
		 
	    if ( event.which == 13 )
	    	login(jQuery(this).parent().parent().attr('id'))
	});

	
	jQuery('.sign-up').bind('keypress','input', function( event ) {
		 
	    if ( event.which == 13 )
	    	sigup(jQuery(this).attr('id'))
	});
	// jQuery('#signup_first_name,[name=last_name]').bind('keypress', function( event ) {
		 
	//     if ( event.which == 13 )
	//     	sigup(jQuery(this).parent().parent().parent().attr('id'))
	// });
});
 

function login(fromNameId) {

	var formData = jQuery('#'+fromNameId).serialize();
	console.log(formData)

	jQuery('#loader').css('display','flex');
	jQuery('#'+fromNameId).find('spam').attr('hidden',true);

	jQuery.ajax({
		url: '{{ URL::to("/customer/login")}}',
		type: "POST",
		data: formData,		
		success: function (res) {
			console.log(res)
			window.location.href ='{{ URL::to("/")}}'
			//jQuery('#loader').css('display','none');
		},
		error: function (reject,exception) {

			jQuery('#loader').css('display','none');

			if( reject.status === 422 ) {
                var errors = jQuery.parseJSON(reject.responseText);
                jQuery.each(errors, function (key, val) {
                	jQuery('[name="'+key+'"]').next('spam').removeAttr('hidden');
                	jQuery('[name="'+key+'"]').next('spam').html(val[0])
                	//notification(val[0]);
                });

            } else {

            	jQuery('#'+fromNameId+'_form_error').removeAttr('hidden');
            	jQuery('#'+fromNameId+'_form_error').html(reject.responseJSON).fadeIn().fadeOut(4000)
            	//notification(reject.responseJSON);
            }

		}
	})
}

function togglePassword() {

	var x = document.getElementById("password");

	if (x.type === "password") {
		x.type = "text";
	} else {
		x.type = "password";
	}
}

function sigup(fromNameId) {

	var formData = jQuery('#'+fromNameId).serialize();
	console.log(formData)

	jQuery('#loader').css('display','flex');
	jQuery('#'+fromNameId).find('spam').attr('hidden',true);

	jQuery.ajax({
		url: '{{ URL::to("/customer/signup")}}',
		type: "POST",
		data: formData,		
		success: function (res) {
			console.log(res)
			window.location.href ='{{ URL::to("/")}}'
			//jQuery('#loader').css('display','none');
		},
		error: function (reject,exception) {

			jQuery('#loader').css('display','none');

			if( reject.status === 422 ) {
                var errors = jQuery.parseJSON(reject.responseText);
                jQuery.each(errors, function (key, val) {
                	jQuery('[name="'+key+'"]').next('spam').removeAttr('hidden');
                	jQuery('[name="'+key+'"]').next('spam').html(val[0])
                	//notification(val[0]);
                });

            } else {

            	jQuery('#'+fromNameId+'_form_error').removeAttr('hidden');
            	jQuery('#'+fromNameId+'_form_error').html(reject.responseJSON).fadeIn().fadeOut(4000)
            	//notification(reject.responseJSON);
            }

		}
	})
}
</script>

@if(!empty($web_setting[77]->value))
	<?=stripslashes($web_setting[77]->value)?>
@endif

</body>
</html>