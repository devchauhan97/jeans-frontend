@extends('layouts')
@section('customcss')
@if(!empty(session("theme")))
        <link href="{!! asset('css/'.session("theme").'.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @else
        
    @endif
    <link href="{!! asset('css/app.css') !!} " media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}">
<!-- <link href="{!! asset('css/responsive.css') !!} " media="all" rel="stylesheet" type="text/css"/> -->
 <!-- <link href="{!! asset('css/rtl.css') !!} " media="all" rel="stylesheet" type="text/css"/> -->
 <link href="{!! asset('css/font-awesome.css') !!} " media="all" rel="stylesheet" type="text/css"/>
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
					<form class="sign-in form-validate" name="signup" enctype="multipart/form-data"  action="{{ URL::to('/process-login')}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
						<div class="form-group">
							<label>Email Address:</label>
							<input type="email" name="email" id="emaill" class="" placeholder="Enter you mail id" value="{{old('emaill')}}">
							<small class="text-danger">{{ $errors->first('email') }}</small>
						</div>
						<div class="form-group">
							<label>Password:</label>
							<input type="password" name="password" id="passwordl" class="" placeholder="Enter you password">
							<small class="text-danger">{{ $errors->first('password') }}</small>
						</div>
						<button class="sign-btn lgbtn" type="submit" name="signin">Sign In</button>
					</form>
						<a href="#" class="forgot-psd">Forgot your password?</a>
						<div class="signin-divide"><span>Or</span></div>
						@if($result['commonContent']['setting'][61]->value==1)
				<!--Google +-->
						<a href="login/google" class="google-btn lgbtn"><i class="fa fa-google-plus"></i>
	                    Sign in With Google
	                    </a>
	                     @endif
						<div class="signin-divide"><span>Or</span></div>
						<!--Facebook-->
                @if($result['commonContent']['setting'][2]->value==1)
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
					<form   enctype="multipart/form-data" class="sign-in" action="{{ URL::to('/signupProcess')}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
						<div>
							<label>Email Address:</label>
							<input type="text" name="emaill" id="email" class="form-control" value="{{ old('email') }}">
							<small class="text-danger">{{ $errors->first('emaill') }}</small>
						</div>
						<div>
							<label>Password:</label>
							<input type="password" class="form-control password" name="passwordl" id="password">
							<small class="text-danger">{{ $errors->first('passwordl') }}</small>
						</div>
						<ul class="req-char">
							<li>8 characters minimum</li>
							<li>At least one letter</li>
							<li>At least one number</li>
						</ul>
						<div>
							<label>Confirm Password:</label>
							<input type="password" class="form-control password" name="re_passwordl" id="re_password">
							<span class="help-block error-content" hidden>@lang('website.Please re-enter your password')</span>
							<small class="text-danger">{{ $errors->first('re_passwordl') }}</small>
						</div>
						<button class="sign-btn register lgbtn" type="submit">Register</button>
					</form>
				</div>
			</div>

		</div>
	</div>
</section>

	
@endsection 	


