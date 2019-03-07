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
	<div class="col-lg-12"><br>

	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
	  <li class="breadcrumb-item active">CheckOut</li>
	</ol>
	<br>
	
		<h4>Sign Up</h4>
		<form name="signup" enctype="multipart/form-data" action="{{ URL::to('/processSignup')}}" method="post">
			
			<div class="form-row">
				<div class="form-group col-md-12">
				  <label for="inputEmail4" class="col-form-label">Email</label>
				  <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
				</div>
				<div class="form-group col-md-12">
				  <label for="inputPassword4" class="col-form-label">Password</label>
				  <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
				</div>
			</div>
			
			<button type="submit" class="btn btn-primary">Login</button>
			<p class="font-small dark-grey-text text-right d-flex justify-content-center mb-3 pt-2"> or Sign in with:</p>

			<div class="row my-3 d-flex justify-content-center">
				<!--Facebook-->
				<button type="button" class="btn btn-white btn-rounded mr-md-3 z-depth-1a"><i class="fa fa-facebook blue-text text-center"></i></button>

				<!--Google +-->
				<button type="button" class="btn btn-white btn-rounded z-depth-1a"><i class="fa fa-google-plus blue-text"></i></button>
			</div>
		</form>


		
	</div>
   </div>
 </section>	
		
@endsection 	


