@extends('layouts')
@section('customcss')
<link rel="stylesheet" type="text/css" href="{!! asset('css/bootstrap.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/responsivej.css') !!}">

@endsection
@section('content')
<!-- col-4-trending -->
@include('common.carouselj')
	<section class="col-4-trending padding-50">
		<div class="wrapper">
			<h2 class="text-center">What's Trending</h2>
			<div class="row">
				<div class="col-md-4 col-sm-4 col-4">
					<img src="{{asset('').'images/trending1.jpg'}}">
					<a class="shop-now-button white" href="{{ URL::to('/shop')}}">Shop Now</a>
				</div>
				<div class="col-md-4 col-sm-4 col-4">
					<img src="{{asset('').'images/trending2.jpg'}}">
					<a class="shop-now-button white" href="{{ URL::to('/shop')}}">Shop Now</a>
				</div>
				<div class="col-md-4 col-sm-4 col-4">
					<img src="{{asset('').'images/trending3.jpg'}}">
					<a class="shop-now-button white" href="{{ URL::to('/shop')}}">Shop Now</a>
				</div>
			</div>
		</div>
	</section>
<!-- banner section -->
	<section class="banner-section">
		 <a title="Banner Image" href="#"><img class="img-fluid" src="{{asset('').'images/banner-1.jpg'}}" alt="Banner Image"></a>
	</section>

	<!-- featured-product -->
	@include('common.products')

<!-- latest-products-section -->
	<section class="latest-products-section padding-50">
		<div class="wrapper">
			<h2 class="text-center">latest products</h2>
			<div class="row">
				<div class="col-md-4 col-sm-4 col-4">
					<a href="#"><img class="left-img" src="{{asset('').'images/trendy_jean4.jpg'}}" alt="Trendy Jean1"></a>
				</div>
				<div class="col-md-4 col-sm-4 col-4">
					<a href="#"><img class="left-img" src="{{asset('').'images/trendy_jean5.jpg'}}" alt="Trendy Jean1"></a>
				</div>
				<div class="col-md-4 col-sm-4 right-img col-4">
					<a href="#"><img src="{{asset('').'images/trendy_jean6.jpg'}}" alt="Trendy Jean3"></a>
				</div>
			</div>
		</div>
	</section>

<!-- view-collection-section -->
	<section class="view-collection-section light-grey padding-50">
		<div class="wrapper">
			<div class="row width-1000">
				<div class="col-md-6 col-sm-6 col-2 first-img">
					<img src="{{asset('').'images/collection1.jpg'}}" alt="collection 1">
				</div>
				<div class="col-md-6 col-sm-6 col-2">
					<div class="inner-content padding-left-70">
						<h3>Lorem ipsum dolor</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi hendrerit dolor eros, vel fringilla nisi viverra vel. Cras cursus interdum dolor, nec rhoncus sem posuere eget. Ut convallis odio sit amet elit porttitor, et blandit odio congue. Morbi placerat id sapien quis molestie.</p>
			        	<a class="shop-now-button grey" href="{{ URL::to('/shop')}}">View Collection</a>

					</div>
				</div>
			</div>

			<div class="row width-1000 padding-top-60">
				<div class="col-md-6 col-sm-6 col-2 order-2">
					<div class="inner-content padding-right-70">
						<h3>Lorem ipsum dolor</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi hendrerit dolor eros, vel fringilla nisi viverra vel. Cras cursus interdum dolor, nec rhoncus sem posuere eget. Ut convallis odio sit amet elit porttitor, et blandit odio congue. Morbi placerat id sapien quis molestie.</p>
			        	<a class="shop-now-button grey" href="{{ URL::to('/shop')}}">View Collection</a>

					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-2 order-1">
					<img src="{{asset('').'images/collection2.jpg'}}" alt="collection 2">
				</div>
			</div>
		</div>
	</section>

<!-- fashion-collection-section -->
	<section class="fashion-collection-section padding-top-60">
		<div class="wrapper">
			<div class="row height-450">
				<div class="col-md-8 col-sm-8 col-left-7">
					<a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/latest_collection1.jpg'}}" alt="collection 2"></a>
					<a href="{{ URL::to('/shop')}}"><img class="padding-top-15" src="{{asset('').'images/trendy_looks.jpg'}}" alt="collection 2"></a>
				</div>

				<div class="col-md-4 col-sm-4 col-right-7">
					<a href="#"><img src="{{asset('').'images/trendy_jean3.jpg'}}" alt="collection 2"></a>
				</div>
			</div>
		</div>
	</section>

<!-- fashion-section -->
	<section class="fashion-section padding-top-15 padding-bottom-50">
		<div class="wrapper">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-left-7">
					<a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/fashion1.jpg'}}" alt="collection 2"></a>
				</div>
				<div class="col-md-6 col-sm-6 col-right-7">
					<a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/cargo.jpg'}}" alt="collection 2"></a>
				</div>

			</div>
		</div>
	</section>

<!-- banner section -->
	<section class="banner-section">
		 <a title="Banner Image" href="{{ URL::to('/shop')}}"><img class="img-fluid" src="{{asset('').'images/banner-2.jpg'}}" alt="Banner Image"></a>
	</section>


@endsection
@section('customjs')

@endsection