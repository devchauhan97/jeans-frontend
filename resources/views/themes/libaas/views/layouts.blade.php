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

@include('common.footerj')
	 
<div id="message_content"></div>
<div class="loader" id="loader">
 	<img src="{{asset('images/loader.gif')}}">
</div>

@if( !auth()->guard('customer')->check() )
<!-- Mobile view login -->
<div class="modal mobile-login" id="mobile-signin">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title"><a href="{{URL::to('/')}}" ><img style="width: 80px;" src="{{getFtpImage($web_setting[15]->value)}}"></a></h4>
			  <button type="button" class="close"  id="signin_pop_up_close"><i style="color:#fff;" class="fa fa-times" aria-hidden="true"></i></button>
			  <div class="clear"></div>
			</div>
			<span>Please enter your Email to Login/Sing Up before you place the order</span>
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
						<input type="hidden" name="_token" value="{{csrf_token()}}">
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
			<a style="" class="forgot" href="{{Url::to('forgot/password')}}">Forgot Password?</a>
			<div class="clear"></div>
			<div class="forgot2">
				<a style="font-weight:500;"  >Not A Member?</a> 
				<a onclick="openModel('signup')">Signup</a>  
			</div>
		</div>
	</div>
</div>

<div class="modal  mobile-login" id="mobile-signup" >
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  	<h4 class="modal-title"><a href="#"><img style="width: 80px;" src="{{getFtpImage($web_setting[15]->value)}}"></a></h4>
		  	<button type="button" class="close" data-dismiss="modal" id="signup_pop_up_close"><i style="color:#fff;" class="fa fa-times" aria-hidden="true"></i></button>
	  		<div class="clear"></div>
		</div>
		<span>Please enter your Email to Login/Sing Up before you place the order</span>
		<div class="modal-body">
			<div class="login-page signup-page">
				<div class="right">
					<div class="row">
						<div class="col-sm-12">
							<div class="login-form">
								<form name="signup_in_mobile" enctype="multipart/form-data" class="sign-up form-validate"  id="signup_in_mobile" action="{{ URL::to('/customer/signup')}}" method="post" >
    								<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="row">
										<div class="col-sm-6 col-xs-6">
											<input type="text" placeholder="First name:" name="first_name" id="first_name" class="form-control field-validate" value="{{ old('first_name') }}" >
											<spam class="help-block error-content" hidden>@lang('website.Please enter your first name')</spam> 
											<small class="text-danger">{{ $errors->first('first_name') }}</small>
										</div>
										<div class="col-sm-6 col-xs-6">
											<input type="text" placeholder="Last name:" name="last_name" id="last_name" class="form-control field-validate"  value="{{ old('last_name') }}">
											<spam class="help-block error-content" hidden>@lang('website.Please enter your last name')</spam> 
											<small class="text-danger">{{ $errors->first('last_name') }}</small>
										</div>
										<div class="col-sm-12">
											<input   type="email" placeholder="Email address:" name="email" id="email" class="mb-0 email-validate" value="{{ old('email') }}">
											<spam class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</spam>
											<small class="text-danger">{{ $errors->first('email') }}</small>
											<span class="span-text">We'll never share your email with anyone else.</span>
										</div>
										<div class="col-sm-12">
											<input class="" type="text" placeholder="Mobile No:" name="mobile_no" id="mobile_no" class="mb-0 field-validate" value="{{ old('mobile_no') }}">
											<spam class="help-block error-content" hidden>@lang('website.Please enter your valid mobile no address')</spam>
											<small class="text-danger">{{ $errors->first('email') }}</small>
										</div>
										<!-- <div class="col-sm-12 text-left">
											<input type="radio" name="gender"  value="Male" @if(!empty(old('gender')) and old('gender') == 'Male' ) checked="checked" @endif)  checked > Male &nbsp;
											<spam class="help-block error-content" hidden>@lang('website.Please select your gender')</spam>
											<input type="radio" name="gender" id="gender" value="Female"   @if(!empty(old('gender')) and old('gender')== 'Female') checked="checked" @endif) > Female<br>
											<small class="text-danger">{{ $errors->first('gender') }}</small>
										</div> -->
										<div class="col-sm-6 col-xs-6">
											<input type="text" placeholder="City:" name="city" id="city" class="mb-0 field-validate" value="{{ old('city') }}">
											<spam class="help-block error-content" hidden>@lang('website.city')</spam>
											<small class="text-danger">{{ $errors->first('city') }}</small>
										</div>
										<div class="col-sm-6 col-xs-6">
											<select name="country"  id="country" class="field-validate">
                                                <option value="">@lang('website.select Country')</option>
                                                @foreach($result['commonContent']['countries'] as $countries)
                                                <option value="{{$countries->countries_id}}" @if(!empty($result['editAddress'])) @if($countries->countries_id==$result['editAddress'][0]->countries_id) selected @endif @endif>{{$countries->countries_name}}</option>
                                                @endforeach
                                            </select>
                                            <spam class="help-block error-content" hidden>@lang('website.Please select country')</spam>
										</div>
									</div>
									<input type="password" placeholder="Password" password" name="password" id="password" class="password">
									<spam class="help-block error-content" hidden>@lang('website.Please enter your password')</spam>
									<small class="text-danger">{{ $errors->first('password') }}</small>
									 <span class="span-text text-left">Minimum 8, minimum requirement 1 lower case, 1 upper case & 1 digit</span>
									<input type="password" placeholder="Confrim Password"  name="re_password" id="re_password" class="field-validate">
									<spam class="help-block error-content" hidden>@lang('website.Please re-enter your password')</spam>
									<span class="help-block error-content-password" hidden>@lang('website.Password does not match the confirm password')</span>
									<small class="text-danger">{{ $errors->first('re_password') }}</small>
									 
									<a class="btn btn-primary btn-dark" onclick="sigup('signup_in_mobile')">Register</a>
								</form>
								<p class="border-bottom text-left ptb-30 span-text" style="font-size: 13px;">By clicking the 'Sign Up' button, you 
										confirm that you accept our Terms of 
										use and Privacy Policy.</p>
								<span class="text-center span-text">Have an account? 
								<a  onclick="openModel('signin')" style="color:blue;">Log In</a></span>
							</div>
						</div>
						<div class="spacer-30"></div>
					</div>
				</div>
			</div>
		</div>
		<p class="span-text text-center p-40">By clicking the 'Sign Up' button, you confirm that 
				you accept our Terms of use and Privacy Policy.</p>
		<div class="clear"></div>
		<div class="forgot2">
			<a style="font-weight:500;">Have an account? </a> 
			<a  onclick="openModel('signin')">Log In</a>
		</div>
	  </div>
	</div>
</div>
@endif
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

	// jQuery("#search_btn").click(function(){
	// 	console.log('search_btn')
	// 	var inp= jQuery('#search_inp').val();
	// 	var cat= jQuery('#cat_inp').val();
	// 	var url ='{{Url::to("/shop?category=")}}'+jQuery('#search_param').val()
	// 	window.location.href=url+'&search='+inp
	// });

	// jQuery("[name=search]").keydown(function (e) {
	//  console.log(e.keyCode)
	//   if (e.keyCode == 13) {
	//   	var inp= jQuery(this).parent().find('[name=search]').val();
	// 	var cat= jQuery(this).parent().find('[name=category]').val();
	// 	var url ='{{Url::to("/shop?category=")}}'+cat
	// 	window.location.href=url+'&search='+inp
	//     //jQuery('#search_btn').click();
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

	var login_page = false;

 	@if(Request::path() == 'login')
 		login_page = true;
	@endif

	if( login_page &&  isMobile ) {

		console.log('mobile view')
		jQuery('#mobile-signin').modal({ show: true });
		jQuery('#desktop-signin-page').hide();

	} else {

		 console.log('destop view')

	}

	jQuery('#signin_pop_up_close').click(function(){
		console.log('close login')
		jQuery('#mobile-signin').modal('hide');

		if( login_page && isMobile ) {

			window.location = '{{URL::to('/')}}'

		}

	});
	// 
	jQuery('.sign-in').bind('keypress','input', function( event ) {
		 
	    if ( event.which == 13 )
	    	login(jQuery(this).attr('id'))
	});

	
	// sign up

	var signup_page = false;

 	@if(Request::path() == 'signup')
 		signup_page = true;
	@endif

	if( signup_page &&  isMobile ) {

		console.log('signup mobile view')
		jQuery('#mobile-signup').modal({ show: true });
		jQuery('#desktop-signup-page').hide();

	} else {

		 console.log('destop view')

	}

	jQuery('#signup_pop_up_close').click(function(){
		console.log('close signup')
		jQuery('#mobile-signup').modal('hide');

		if( signup_page && isMobile ) {

			window.location = '{{URL::to('/')}}'

		}

	});

	jQuery('.sign-up').bind('keypress','input', function( event ) {
		 
	    if ( event.which == 13 )
	    	sigup(jQuery(this).attr('id'))
	});
	 

	// jQuery('#signup_first_name,[name=last_name]').bind('keypress', function( event ) {
		 
	//     if ( event.which == 13 )
	//     	sigup(jQuery(this).parent().parent().parent().attr('id'))
	// });

	//function togglePassword() {
	jQuery('#toggle_password').click(function(){
		// var x = document.getElementById("password");

		// if (x.type === "password") {
		// 	x.type = "text";
		// } else {
		// 	x.type = "password";
		// }
		//jQuery(this).attr('type')
		if(jQuery(this).attr('type') == text)
			jQuery(this).find('[type=password]').attr('type','password')
		else
			jQuery(this).find('[type=password]').attr('type','text')
	})
	jQuery('#cart-icon-btn').click(function(){
		 
		jQuery('.shopping-cart').show()
	})

	jQuery('#cart-close').click(function(){
		 
		jQuery('.shopping-cart').hide()
	})
	

});
 
function openModel(modalId) {
	var close_form= modalId == 'signin' ? 'signup':modalId
	jQuery('#mobile-'+close_form).modal('hide');
	jQuery('#mobile-'+modalId).modal({ show: true });
}
// function closeModel(modalId) {
// 	jQuery('#mobile-'+modalId).modal('hide');
	
// }

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