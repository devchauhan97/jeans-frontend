@extends('layouts')
@section('customcss')
@if(!empty(session("theme")))
    <link href="{!! asset('public/css/'.session("theme").'.css') !!} " media="all" rel="stylesheet" type="text/css"/>
@else
    
@endif
<link href="{!! asset('public/css/app.css') !!} " media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{!! asset('public/css/style.css') !!}">
<!-- <link href="{!! asset('css/responsive.css') !!} " media="all" rel="stylesheet" type="text/css"/> -->
 <!-- <link href="{!! asset('css/rtl.css') !!} " media="all" rel="stylesheet" type="text/css"/> -->
 <link href="{!! asset('public/css/font-awesome.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <!-- <link href="{!! asset('css/owl.carousel.css') !!} " media="all" rel="stylesheet" type="text/css"/> -->
 <!-- <link href="{!! asset('css/bootstrap-select.css') !!} " media="all" rel="stylesheet" type="text/css"/> -->
  
@endsection
@section('content')

<!-- login-register -->
<section class="login-register">
	<div class="wrapper">
		<div class="row sign-in-form">
			<div class="col-md-6 sign-in-left">
				<div class="sign-in-inner">
					<h3 class="text-center">Sign In</h3>
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
					<form class="sign-in" name="signup" enctype="multipart/form-data"  action="{{ URL::to('/customer/login')}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
						<div class="form-group">
							<label>Email Address:</label>
							<input type="email" name="log_email" id="log_email" class="form-control" placeholder="Enter you mail id" value="{{old('log_email')}}">
							<small class="text-danger">{{ $errors->first('log_email') }}</small>
						</div>
						<div class="form-group">
							<label>Password:</label>
							<input type="password" name="log_password" id="log_password" class="form-control" placeholder="Enter you password">
							<small class="text-danger">{{ $errors->first('log_password') }}</small>
						</div>
						<button class="sign-btn lgbtn" type="submit" name="signin">Sign In</button>
					</form>
					<a href="{{Url::to('forgot/password')}}" class="forgot-psd">Forgot your password?</a>
					<div class="signin-divide"><span>Or</span></div>
					@if( $result['commonContent']['setting'][61]->value==1 )
					<!--Google +-->
						<a href="login/google" class="google-btn lgbtn"><i class="fa fa-google-plus"></i>
	                    Sign in With Google
	                    </a>
                     @endif
					<div class="signin-divide"><span>Or</span></div>
					<!--Facebook-->
	                @if( $result['commonContent']['setting'][2]->value==1 )
		                <a href="login/facebook"  class="facebook-btn lgbtn"><i class="fa fa-facebook"></i>
						
		                Sign in With Facebook
		                </a>
	                @endif
		        </div>
			</div>
			<div class="col-md-6 sign-in-right">
				<div class="sign-in-inner">
					<h3 class="text-center">New to Jeans</h3>		
				
				@if(Session::has('error'))
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">@lang('website.Error'):</span>
						  {!! session('error') !!}
                          
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
					</div>
				@endif

				@if(Session::has('success'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">@lang('website.Success'):</span>
						  {!! session('success') !!}
                          
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          		<span aria-hidden="true">&times;</span>
                          </button>
					</div>
				@endif
					<form   enctype="multipart/form-data" class="sign-in" action="{{ URL::to('/customer/signup')}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
						<div>
							<label>First Name <strong>*</strong> :</label>
							<input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}">
							<small class="text-danger">{{ $errors->first('first_name') }}</small>
						</div>
						<div>
							<label>Last Name:</label>
							<input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}">
							<small class="text-danger">{{ $errors->first('last_name') }}</small>
						</div>

						<div>
							<label>@lang('website.Email Address') <strong>*</strong> :</label>
							<input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}">
							<small class="text-danger">{{ $errors->first('email') }}</small>
						</div>
						<div >
							<label > @lang('website.Gender') <strong>*</strong> </label>
							    <select class="custom-select field-validate" name="gender" id="inlineFormCustomSelect">
									<option selected value="">@lang('website.Choose...')</option>
									<option value="Male" @if(!empty(old('gender')) and old('gender')== 'Male') selected @endif)>@lang('website.Male')</option>
									<option value="Female" @if(!empty(old('gender')) and old('gender')== 'Female') selected @endif>@lang('website.Female')</option>
								</select>
								<small class="text-danger">{{ $errors->first('gender') }}</small>
							 
						</div>
						<div>
							<label>Password <strong>*</strong>:</label>
							<input type="password" class="form-control password" name="password" id="password">
							<small class="text-danger">{{ $errors->first('password') }}</small>
						</div>
						<ul class="req-char">
							<li>8 characters minimum</li>
							<li>At least one letter</li>
							<li>At least one number</li>
						</ul>
						<div>
							<label>Confirm Password <strong>*</strong>:</label>
							<input type="password" class="form-control password" name="re_password" id="re_password">
							<span class="help-block error-content" hidden>@lang('website.Please re-enter your password')</span>
							<small class="text-danger">{{ $errors->first('re_password') }}</small>
						</div>
						 
						<!-- <div >
							<label class="form-check-label">
								<input class="form-check-input" type="checkbox">@lang('website.Creating an account means you are okay with our') 
								 @if(!empty($result['commonContent']['pages'][3]->slug))
								<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][3]->slug)}}">@endif @lang('website.Terms and Services')
								@if(!empty($result['commonContent']['pages'][3]->slug))</a>@endif, @if(!empty($result['commonContent']['pages'][1]->slug))<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][1]->slug)}}">@endif @lang('website.Privacy Policy')@if(!empty($result['commonContent']['pages'][1]->slug))</a> @endif and @if(!empty($result['commonContent']['pages'][2]->slug))<a href="{{ URL::to('/page?name='.$result['commonContent']['pages'][2]->slug)}}">@endif @lang('website.Refund Policy') @if(!empty($result['commonContent']['pages'][3]->slug))</a>@endif.
							</label>
							<span class="help-block error-content" hidden>@lang('website.Please accept our terms and conditions')</span>
						</div> -->
	                         
						<button class="sign-btn register lgbtn" type="submit">Register</button>
					</form>
				</div>
			</div>

		</div>
	</div>
</section>

	
@endsection 	


