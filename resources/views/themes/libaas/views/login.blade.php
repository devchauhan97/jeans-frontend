@extends('layouts')
 
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> <!-- Popper plugin for Bootstrap -->
<section class="page-header" style="height: 120px;">
</section>
<!-- Site Content -->
<section class="content main-container" id="site-content">
	<div class="ptb-40">
		<div class="container">
			<div class="row">
				<div class="offset-md-3 col-md-6">
					<div class="login-page" id="desktop-signin-page">
						<div class="left">
							<img class="width-100 img-fluid" style="object-fit: cover" src="{{ asset('images/login-img.jpg') }}">
						</div>
						<div class="right">
							<h2>Login</h2>
							<div class="spacer-30"></div>
							<div class="row text-center">
								<dov class="col-sm-6">
									<a class="btn btn-primary" >Sign In</a>
								</dov>
								<dov class="col-sm-6">
									<a class="btn btn-primary btn-secondary" href="{{URL::to('/signup')}}">Sign Up</a>
								</dov>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="login-form">
										<form   class="sign-in form-validate"  id="sign_in_desktop" enctype="multipart/form-data"  action="{{ URL::to('/customer/login')}}" method="post">
											<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
											<!-- <label for="uname"><b>Username</b></label> -->
											<input type="email" placeholder="Your Email" name="email" id="email" class="email-validate"  value="{{old('email')}}">
											<spam class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</spam>
											<small class="text-danger">{{ $errors->first('email') }}</small>
	
											<!-- <label for="psw"><b>Password</b></label> -->
											<input type="password" placeholder="Your Password" name="password" id="password" class="field-validate"  value="{{old('password')}}">
											<spam class="help-block error-content" hidden>@lang('website.Please enter your password')</spam>
											<small class="text-danger">{{ $errors->first('password') }}</small>
											<div class="checkbox">
												<label>
													<input type="checkbox" name="remember" value="1"  id="remember" {{ old('remember') ? 'checked' : '' }} /> Remember me
												</label>    
												<a href="{{Url::to('forgot/password')}}">Forgot</a>
											</div>
											<spam class="help-block error-content"  id="sign_in_desktop_form_error" hidden></spam>
											<!-- <a onclick="login('sign_in_desktop')" class="btn btn-primary btn-dark btn-block">Login</a> -->
											 <a class="btn btn-primary btn-dark" onclick="login('sign_in_desktop')">Login</a>
										</form>
									</div>
								</div>
								<div class="col-sm-12">
									<ul class="popup-social">
										@if( $result['commonContent']['setting'][61]->value == 1)
										<li><a href="{{URL::to('login/google')}}"><i class="fa fa-google-plus" aria-hidden="true"></i> Google +</a></li>
										@endif
										@if( $result['commonContent']['setting'][2]->value == 1)
										<li><a href="{{URL::to('login/facebook')}}"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a></li>
										@endif
									</ul>
								</div>
								<div class="spacer-30"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>

</section>

@endsection 	


