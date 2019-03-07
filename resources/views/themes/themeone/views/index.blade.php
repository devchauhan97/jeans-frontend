@extends('layouts')
@section('customcss')
<link rel="stylesheet" type="text/css" href="{!! asset('css/bootstrap.min.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/style.min.css') !!}">
<!-- <link rel="stylesheet" type="text/css" href="{!! asset('css/responsivej.min.css') !!}"> -->
@endsection
@section('content')
<!-- col-4-trending -->
@include('common.carouselj')
 
<!-- banner section -->
@include('common.category_slider')
<!-- occasion-subcategory -->
@include('common.occasion_slider')
<!-- occasion-subcategory -->
@include('common.bridal_lehengas')
<!-- top-seller -->
@include('common.top_seller')
 
<section class="col-4-trending padding-50">
	<div class="wrapper">
		<div class="row">
			@foreach($result['page_section_bottom'] as $page_section)
			<div class="col-md-4 col-sm-4 col-4">
				<img src="{{getFtpImage($page_section->sections_image)}}">
				@if($page_section->type == 'category')
				<a class="shop-now-button white" href="{{ URL::to('/shop?category='.$page_section->sections_url)}}">
				@elseif($page_section->type == 'product')
					<a class="shop-now-button white" href="{{ URL::to('/product-detail/'.$page_section->sections_url)}}">
				@endif
				 Shop Now</a>
			</div>
			@endforeach
		</div>
	</div>
</section>

@include('common.spot_light_product')
 
<!-- top-deals -->

@include('common.blogs')
@endsection
@section('customjs')

@endsection