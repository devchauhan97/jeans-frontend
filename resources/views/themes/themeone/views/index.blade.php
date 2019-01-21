@extends('layouts')
@section('customcss')
<link rel="stylesheet" type="text/css" href="{!! asset('public/css/bootstrap.min.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('public/css/style.min.css') !!}">
<!-- <link rel="stylesheet" type="text/css" href="{!! asset('public/css/responsivej.min.css') !!}"> -->
@endsection
@section('content')
<!-- col-4-trending -->
@include('common.carouselj')
	<section class="col-4-trending padding-50">
		<div class="wrapper">
			<h2 class="text-center">What's Trending</h2>
			<div class="row">
				<div class="col-md-4 col-sm-4 col-4">
					<img src="{{asset('public/images/trending1.jpg')}}">
					<a class="shop-now-button white" href="{{ URL::to('/shop')}}">Shop Now</a>
				</div>
				<div class="col-md-4 col-sm-4 col-4">
					<img src="{{asset('public/images/trending2.jpg')}}">
					<a class="shop-now-button white" href="{{ URL::to('/shop')}}">Shop Now</a>
				</div>
				<div class="col-md-4 col-sm-4 col-4">
					<img src="{{asset('public/images/trending3.jpg')}}">
					<a class="shop-now-button white" href="{{ URL::to('/shop')}}">Shop Now</a>
				</div>
			</div>
		</div>
	</section>
	<!-- banner section -->
	 

<!-- featured-product -->
@include('common.featured')
<!-- latest-products-section -->

<!-- top-seller -->
@include('common.top_seller')
<!-- top-deals -->
@include('common.special_products')
	

@endsection
@section('customjs')

@endsection