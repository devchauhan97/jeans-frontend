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
					<div class="login-page signup-page" id="desktop-signup-page">
						<div class="left">
							<img class="width-100 img-fluid" style="object-fit: cover" src="{{ asset('images/signup-img.jpg') }}">
						</div>
						<div class="right">
							<h2>Sign Up</h2>
							<div class="spacer-30"></div>
							<div class="row text-center">
								<dov class="col-sm-6">
									<a class="btn btn-primary btn-secondary" href="{{URL::to('/login')}}">Sign In</a>
								</dov>
								<dov class="col-sm-6">
									<a class="btn btn-primary" >Sign Up</a>
								</dov>
							</div>
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
							<div class="row">
								<div class="col-sm-12">
									<div class="login-form">
										<form name="signup_in_desktop" enctype="multipart/form-data" class="sign-up form-validate"  id="signup_in_desktop" action="{{ URL::to('/customer/signup')}}" method="post" >
            								<input type="hidden" name="_token" value="{{csrf_token()}}">
											<div class="row">
												<div class="col-sm-6">
													<input type="text" placeholder="First name:" name="first_name" id="first_name" class="form-control field-validate" value="{{ old('first_name') }}" >
													<spam class="help-block error-content" hidden>@lang('website.Please enter your first name')</spam> 
													<small class="text-danger">{{ $errors->first('first_name') }}</small>
												</div>
												<div class="col-sm-6">
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
													<input class="" type="text" placeholder="Mobile No:" name="mobile_no" id="mobile_no"  class="mb-0 field-validate" value="{{ old('mobile_no') }}">
													<spam class="help-block error-content" hidden>@lang('website.Please enter your valid mobile no address')</spam>
													<small class="text-danger">{{ $errors->first('email') }}</small>
												</div>
												<!-- <div class="col-sm-12 text-left">
													<input type="radio" name="gender"  value="Male" @if(!empty(old('gender')) and old('gender') == 'Male' ) checked="checked" @endif)  checked > Male &nbsp;
													<spam class="help-block error-content" hidden>@lang('website.Please select your gender')</spam>
													<input type="radio" name="gender" id="gender" value="Female"   @if(!empty(old('gender')) and old('gender')== 'Female') checked="checked" @endif) > Female<br>
													<small class="text-danger">{{ $errors->first('gender') }}</small>
												</div> -->
												<div class="col-sm-6">
													<input type="text" placeholder="City:" name="city" id="city" class="mb-0 field-validate" value="{{ old('city') }}">
													<spam class="help-block error-content" hidden>@lang('website.city')</spam>
													<small class="text-danger">{{ $errors->first('city') }}</small>
												</div>
												<div class="col-sm-6">
													<select name="country"  id="country" class="field-validate">
	                                                    <option value="">@lang('website.select Country')</option>
	                                                    @foreach($result['countries'] as $countries)
	                                                    <option value="{{$countries->countries_id}}" @if(!empty($result['editAddress'])) @if($countries->countries_id==$result['editAddress'][0]->countries_id) selected @endif @endif>{{$countries->countries_name}}</option>
	                                                    @endforeach
	                                                </select>
	                                                <spam class="help-block error-content" hidden>@lang('website.Please select country')</spam>
												</div>
											</div>
											<input type="password" placeholder="Password" password" name="password" id="password" class="password field-validate">
											<spam class="help-block error-content" hidden>@lang('website.Please enter your password')</spam>
											<small class="text-danger">{{ $errors->first('password') }}</small>
											<span class="span-text text-left">Minimum 8, minimum requirement 1 lower case, 1 upper case & 1 digit.</span>
											<!-- <ul class="req-char text-left">
												<li>8 characters minimum</li>
												<li>At least one upper and lower letter</li>
												<li>At least one number</li>
											</ul> -->
											<input type="password" placeholder="Confrim Password"  name="re_password" id="re_password" class="field-validate">
											<spam class="help-block error-content" hidden>@lang('website.Please re-enter your password')</spam>
											<span class="help-block error-content-password" hidden>@lang('website.Password does not match the confirm password')</span>
											<small class="text-danger">{{ $errors->first('re_password') }}</small>
											
											<div class="checkbox">
												<label>
													<input type="checkbox" checked="checked" value="1" name="remember"> Remember me
												</label>    
												<a href="{{Url::to('forgot/password')}}">Forgot</a>
											</div>
											<a class="btn btn-primary btn-dark" onclick="sigup('signup_in_desktop')">Register</a>
										</form>
										<p class="border-bottom text-left ptb-30 span-text" style="font-size: 13px;">By clicking the 'Sign Up' button, you 
												confirm that you accept our Terms of 
												use and Privacy Policy.</p>
										<span class="text-center span-text">Have an account? <a href="{{URL::to('/login')}}" style="color:blue;">Log In</a></span>
									</div>
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


