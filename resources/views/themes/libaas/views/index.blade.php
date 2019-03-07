@extends('layouts')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> <!-- Popper plugin for Bootstrap -->
<script src="{!! asset('vendor/flexslider/js/jquery.flexslider.js') !!}"></script> <!-- jQuery FlexSlider plugin -->
<script src="{!! asset('vendor/magnific/jquery.magnific-popup.min.js') !!}"></script> <!-- jQuery Magnific Popup plugin -->
 <!-- jQuery Owl Carousel plugin -->
 <script src="{!! asset('js/jquery.parallax.js') !!}"></script> <!-- jQuery Parallax plugin -->
<script src="{!! asset('js/jquery.sticky.plugin.js') !!}"></script> <!-- jQuery sticky plugin -->
<script src="{!! asset('js/jquery.fitvids.js') !!}"></script> <!-- jQuery FitVids plugin -->
<script src="{!! asset('js/jquery.superfish.menu.js') !!}"></script> <!-- jQuery Superfish plugin -->
<script src="{!! asset('js/tweetie.twitter.feeds.js') !!}"></script> 
<!-- jQuery Twitter Feeds plugin -->
<script src="{!! asset('vendor/owl-carousel/js/owl.carousel.min.js') !!}"></script>
<!-- jQuery Owl Carousel plugin -->

<script src="{!! asset('js/init.js') !!}"></script> <!-- Template js functions initialization -->
<!-- col-4-trending -->
<section class="page-header">
	<div class="hero-area">
		<div class="owl-carousel owl-one owl-theme">
			@foreach($result['slides'] as $key=>$slides_data)
			<div class="item" style="background-image: url({{getFtpImage($slides_data->image)}})">
				<div class="hero-area-in">
					<span>{{$slides_data->title}}</span>
					<h2><span>Sarees &</span>  <br>Sherwani</h2>
					@if($slides_data->type == 'category')
						<a href="{{ URL::to('/shop?category='.$slides_data->url)}}" class="btn btn-primary">
					@elseif($slides_data->type == 'product')
						<a href="{{ URL::to('/product-detail/'.$slides_data->url)}}" class="btn btn-primary">
					@elseif($slides_data->type == 'mostliked')
						<a href="{{ URL::to('shop?type=mostliked')}}" class="btn btn-primary">
					@elseif($slides_data->type == 'topseller')
						<a href="{{ URL::to('shop?type=topseller')}}" class="btn btn-primary">
					@elseif($slides_data->type == 'special')
						<a href="{{ URL::to('shop?type=special')}}" class="btn btn-primary">
					@endif 
					  Shop Now
				    </a>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>
 <section class="content main-container" id="site-content">
		<div class="ptb-40">
			<div class="container">
				<div class="owl-carousel owl-two owl-theme">
				    <div class="item">
				        <div class="cate-box box-border bottom-box-shadow">
				            <img src="{{getFtpImage($result['new_arrival']->products_image)}}">
				            <h3>New Arrivals</h3>
				            <a href="{{ URL::to('/shop')}}"><i class="fas fa-arrow-right"></i></a>
				        </div>
				    </div>
				   @foreach($result['category_slides'] as $key=>$cat) 
				    <div class="item">
				        <div class="cate-box box-border bottom-box-shadow">
				            <img src="{{getFtpImage($cat->categories_image)}}">
				            <h3>{{$cat->categories_name}}</h3>
				            <a href="{{ URL::to('/shop?category='.$cat->categories_slug)}}"><i class="fas fa-arrow-right"></i></a>
				        </div>
				    </div>
				    @endforeach        
				</div>
			</div>
			<div class="container text-center mtb-40">
				<h3 class="">Occasion</h3>
			</div>
			<div class="container">
				<div class="owl-carousel owl-three owl-theme  box-border">
					@foreach($result['occasion_tags_slides'] as $key=>$cat) 
					<div class="item">
						<div class="cate-box">
							<img src="{{getFtpImage($cat->product_tag_image)}}">
							<div class="cate-box-in" @if($key > 0 && $key%2 != 0) style="right:
							70%; left: inherit;" @endIf>
								<p>New</p>
								<h3>{{$cat->name}}</h3>
								<a href="{{ URL::to('/shop?filters_applied=1&product_tags[]='.$cat->name)}}"><i class="fas fa-arrow-right"></i></a>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			<div class="spacer-30 d-lg-none d-md-none"></div>
			<div class="container text-center mtb-40 mtb-15-m">
				<h3 class="">Bridal Lehengas</h3>
			</div>
			<div class="container">
				<div class="owl-carousel owl-four owl-theme ">
				    @foreach($result['bridal_lehengas'] as $key=>$products) 

				    <div class="item">

				        <div class="product-box box-border bottom-box-shadow">
				            <img src="{{getFtpImage($products->products_image)}}" lt="{{$products->products_name}}" >
				            <?php    $current_date = date("Y-m-d", strtotime("now"));
				                $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
				                $date=date_create($string);
				                date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
				                $after_date = date_format($date,"Y-m-d"); 

				                if($after_date>=$current_date){
				                    print '<div class="badge">N<br>E<br>W</div>';
				                }
				             
				                $parm='?';
				                if($products->default_products_attributes){
				                    $parm.= $products->default_products_attributes->default_products_option->products_options_name;
				                    $parm.= '='.$products->default_products_attributes->default_products_options_values->products_options_values_id;
				                }else{
				                    $parm='';
				                }
				            ?>
				            <div class="product-action">
				                <div class="action">
				                    <ul>
				                        <li><a class="is_liked" products_id="{{$products->products_id}}"><i   class="fa @if( $products->liked_customers_id ) fa-heart @else fa-heart-o @endif "  aria-hidden="true"></i></a></li>
				                        <li><a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
				                        <li><a href="{{ URL::to('/product-detail/'.$products->products_slug.$parm)}}"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
				                    </ul>
				                </div>
				                <div class="clear"></div>
				                <div class="action-title">
				                    <p>
				                        {{$products->products_name}}
				                    </p>
				                    <strong>
				                        @if(!empty($products->discount_price))
				                       {{$web_setting[19]->value}}{{$products->discount_price+0}}
				                        <span class="text-strike">
				                        <strike>{{$web_setting[19]->value}}{{$products->products_price+0}}</strike>
				                        </span>
				                         @else 
				                        <span class="pink">{{$web_setting[19]->value}}{{$products->products_price+0}}</span>
				                         @endif
				                    </strong>
				                </div>
				            </div>
				        </div>
				    </div>
				     @endforeach 
				</div>
			</div>
			<div class="spacer-40 d-xs-none-cu"></div>
			<div class="text-center">
				<a class="btn btn-primary" href="{{Url::to('/shop?category='.$result['categories_slug'])}}">See All</a>
			</div>
			<div class="container text-center mtb-40  mtb-15-m">
				<h3 class="">Shop The Look</h3>
			</div>
			<div class="container">
				<div class="owl-carousel owl-four owl-theme ">
			    @foreach($result['top_sellers'] as $key=>$products) 
				    <div class="item">
				        <div class="product-box box-border bottom-box-shadow">
				            <img src="{{getFtpImage($products->products_image)}}" lt="{{$products->products_name}}" >
				            <?php    $current_date = date("Y-m-d", strtotime("now"));
				                $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
				                $date=date_create($string);
				                date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
				                $after_date = date_format($date,"Y-m-d"); 

				                if($after_date>=$current_date){
				                    print '<div class="badge">N<br>E<br>W</div>';
				                }
				             
				                $parm='?';
				                if($products->default_products_attributes){
				                    $parm.= $products->default_products_attributes->default_products_option->products_options_name;
				                    $parm.= '='.$products->default_products_attributes->default_products_options_values->products_options_values_id;
				                }else{
				                    $parm='';
				                }
				            ?>
				            <div class="product-action">
				                <div class="action">
				                    <ul>
				                        <li><a class="is_liked" products_id="{{$products->products_id}}"><i   class="fa @if( $products->liked_customers_id == session('customers_id') ) fa-heart @else fa-heart-o @endif "  aria-hidden="true"></i></a></li>

				                        <li><a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
				                        <li><a href="{{ URL::to('/product-detail/'.$products->products_slug.$parm)}}"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
				                    </ul>
				                </div>
				                <div class="clear"></div>
				                <div class="action-title">
				                    <p>
				                        {{$products->products_name}}
				                    </p>
				                    <strong>
				                        @if(!empty($products->discount_price))
				                       {{$web_setting[19]->value}}{{$products->discount_price+0}}
				                        <span class="text-strike">
				                        <strike>{{$web_setting[19]->value}}{{$products->products_price+0}}</strike>
				                        </span>
				                         @else 
				                        <span class="pink"> {{$web_setting[19]->value}}{{$products->products_price+0}}</span>
				                         @endif
				                    </strong>
				                </div>
				            </div>
				        </div>
				    </div>
				     @endforeach 
				</div>
			</div>
			<div class="spacer-40 d-xs-none-cu"></div>
			<div class="text-center">
				<a class="btn btn-primary btn-secondary" href="{{Url::to('/shop?type=topseller')}}">See All</a>
			</div>
		</div>
		@foreach($result['page_section_bottom'] as $key => $page_section)
		@if($key == 0)
		<div class="home-showcase">

			<!-- <img class="img-fluid mx-auto  d-xs-none d-xs-none-cu" src="{{getFtpImage($page_section->sections_image)}}" alt="">
			 
			<img class="img-fluid mx-auto  d-xs-none d-xs-none-cu" src="{{getFtpImage($page_section->sections_image)}}" alt=""> -->
			 <img class="img-fluid mx-auto  d-xs-none d-xs-none-cu" src="images/home10.jpg">
			<img class="img-fluid mx-auto d-block d-lg-none d-md-none" src="images/home10-1.jpg">

			<div class="home-showcase-in">
				<span>{{$page_section->sections_title}}</span>
				 
				@if($page_section->type == 'category')
				<h2>{{$page_section->categories_name}} </h2>
				<a class="btn btn-primary" href="{{ URL::to('/shop?category='.$page_section->sections_url)}}">
				@elseif($page_section->type == 'product')
				<h2>{{$page_section->products_name}} </h2>
					<a class="btn btn-primary" href="{{ URL::to('/product-detail/'.$page_section->sections_url)}}">
				@endif
				 Shop Now</a>
				 
		</div>
		@endIf
		@endforeach
		</div>
		<div class="clear"></div>
		<div class="home-detail ptb-40">
			<div class="container">
				<div class=" text-center mb-40 mb-20-m">
					<h3 class="">Product Spotlight</h3>
				</div>
				<div class="owl-carousel owl-five owl-theme">
				 @foreach($result['spot_light_product'] as $key=>$products) 
				    <?php
				        $parm='?';
				        if($products->default_products_attributes){
				            $parm.= $products->default_products_attributes->default_products_option->products_options_name;
				            $parm.= '='.$products->default_products_attributes->default_products_options_values->products_options_values_id;
				        }else{
				            $parm='';
				        }
				    ?>
				    <div class="item">
				        <div class="row">
				            <div class="offset-md-1 col-md-10 col-sm-12">
				                <div class="row align-items-center">
				                    <div class="col-md-6">
				                        <div class="detail-img box-border bottom-box-shadow">
				                            <img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}">
				                        </div>
				                    </div>
				                    <div class="col-md-6">
				                        <h3>{{$products->products_name}}</h3>
				                            <p>RIF : {{$products->products_model}}</p>
				                        <h3>@if(!empty($products->discount_price))
				                       {{$web_setting[19]->value}}{{$products->discount_price+0}}
				                        <span class="text-strike">
				                        <strike>{{$web_setting[19]->value}}{{$products->products_price+0}}</strike>
				                        </span>
				                         @else 
				                        <span class="pink"> {{$web_setting[19]->value}}{{$products->products_price+0}}</span>
				                         @endif</h3>
				                        <p>{{$products->sort_description}}</p>
				                        <a class="btn btn-primary btn-secondary" href="{{Url::to('/shop')}}">See All</a>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>
				    @endforeach
				</div>
 
			</div>
		</div>
		<div class="clear"></div>
		<div class="blog-area ptb-40">
			<div class="container text-center ">
				<h3 class="mb-40 mb-20-m">Blog Posts</h3>
			</div>
			<div class="container">
				<div class="owl-carousel owl-six owl-theme ">
				    @foreach($result['blogs'] as $key=>$list) 
				    <div class="item">
				        <div class="blog-box box-border bottom-box-shadow">
				            <img  src="{{getFtpImage($list->image)}}" alt="{{$list->title}}">
				            <div class="blog-action">
				                <div class="clear"></div>
				                <div class="action-title">
				                    <p>
		                            	{{$list->title}}
				                    </p>
				                </div>
				            </div>
				        </div>
				        <div class="clear"></div>
				        <div class="blog-text">
				            <p>{{$list->sort_description}}</p>
				            <a href="{{ URL::to('/blog/'.$list->blogs_id)}}" class="btn btn-primary btn-secondary">Read More</a>
				        </div>
				    </div>
				    @endforeach
				</div>
			</div>
		</div>
	</section>
<script>
jQuery(document).ready(function(){
	jQuery('.owl-one').owlCarousel({
		loop:true,
		// margin:10,
		items:1,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			600:{
				items:1,
				nav:false
			},
			1000:{
				items:1,
				nav:true,
				loop:false
			},
			1500:{
				items:1,
				nav:true,
				loop:false
			}
		}
	})
	jQuery( ".owl-prev").html('<i class="fa fa-chevron-left" aria-hidden="true"></i>');
	jQuery( ".owl-next").html('<i class="fa fa-chevron-right" aria-hidden="true"></i>');
 
	jQuery('.owl-two').owlCarousel({
		loop:true,
		margin:30,
		items:3,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			450:{
				items:2,
				nav:false
			},
			800:{
				items:3,
				nav:false
			},
			1000:{
				items:3,
				nav:true,
				loop:false
			},
			1500:{
				items:3,
				nav:true,
				loop:false
			}
		}
	})
	 
 
	jQuery('.owl-three').owlCarousel({
		loop:true,
		//margin:30,
		items:2,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			767:{
				items:2,
				nav:false
			},
			1000:{
				items:2,
				nav:true,
				loop:false
			},
			1500:{
				items:2,
				nav:true,
				loop:false
			}
		}
	})
	
 
	jQuery('.owl-four').owlCarousel({
		loop:true,
		margin:30,
		items:4,
		responsiveClass:true,
		responsive:{
			0:{
				items:2,
				nav:true,
				margin:15
			},
			800:{
				items:3,
				nav:false
			},
			1000:{
				items:4,
				nav:true,
				loop:false
			},
			1500:{
				items:4,
				nav:true,
				loop:false
			}
		}
	})
	 
 
	jQuery('.owl-five').owlCarousel({
		loop:true,
		//margin:30,
		items:1,
		responsiveClass:true,
		responsive:{
			0:{
				items:1,
				nav:true
			},
			600:{
				items:1,
				nav:false
			},
			1000:{
				items:1,
				nav:true,
				loop:false
			},
			1500:{
				items:1,
				nav:true,
				loop:false
			}
		}
	})
	
 
	jQuery('.owl-six').owlCarousel({
		loop:true,
		margin:30,
		items:4,
		responsiveClass:true,
		responsive:{
			0:{
				items:2,
				nav:true
			},
			800:{
				items:3,
				nav:false
			},
			1000:{
				items:4,
				nav:true,
				loop:false
			},
			1500:{
				items:4,
				nav:true,
				loop:false
			}
		}
	})
	 
	jQuery( ".owl-prev").html('<img src="{!! asset("images/slider-left-arrow.png") !!}">');
	jQuery( ".owl-next").html('<img src="{!! asset("images/slider-right-arrow.png") !!}">');
})
</script>
@endsection
 