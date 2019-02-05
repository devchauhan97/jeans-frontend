@extends('layouts')
@section('customcss')
@if(!empty(session("theme")))
        <link href="{!! asset('css/'.session("theme").'.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @else
        <link href="{!! asset('css/app.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @endif
<link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}">
<link href="{!! asset('css/responsive.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/rtl.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/font-awesome.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/owl.carousel.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/bootstrap-select.css') !!} " media="all" rel="stylesheet" type="text/css"/>
  
@endsection
@section('content')
<section class="site-content">
<div class="container">
<div class="breadcum-area">
    <div class="breadcum-inner">
        <h3>@lang('website.Reset Password')</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
            <li class="breadcrumb-item active">@lang('website.Reset Password')</li>
        </ol>
    </div>
</div>
<div class="registration-area">
	
	<div class="row justify-content-center">
		<div class="col-12 col-md-6 col-lg-5 registered-customers">
             
            <h5 class="title-h4">
				@lang('website.Reset Password')
			</h5>
		 	 @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">@lang('website.Error'):</span>
                    {!! session('loginError') !!}
                    
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    	<span aria-hidden="true">&times;</span>
                    </button>
                </div>
               @endif
			 <form name="updateresetpassword" class="" enctype="multipart/form-data" action="{{ URL::to('update/reset/password')}}" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                 <input type="hidden" name="token" value="{{$result['password_reset']->token}}">
                 
                <div class="form-group row">
                    <label for="new_password" class="col-sm-4 col-form-label">@lang('website.New Password')</label>
                    <div class="col-sm-8">
                        <input name="new_password" type="password" class="form-control" id="new_password" placeholder="@lang('website.New Password')">
                        <span class="help-block error-content" hidden>@lang('website.Please enter your password and should be at least 6 characters long')</span>
                        <small class="text-danger">{{ $errors->first('new_password') }}</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="confirm_password" class="col-sm-4 col-form-label">@lang('website.Confirm Password')</label>
                    <div class="col-sm-8">
                        <input name="confirm_password" type="password" class="form-control" id="confirm_password" placeholder="@lang('website.Confirm Password')">
                        <span class="help-block error-content" hidden>@lang('website.Please enter your Confirm password')</span>
                        <small class="text-danger">{{ $errors->first('confirm_password') }}</small>
                    </div>
                </div>
                <div class="button">
                    <button type="submit" class="btn btn-dark">@lang('website.Update')</button>
                </div>
            </form>
 		</div>
	</div>
</div> 
</div>
</section>
	
@endsection 	


