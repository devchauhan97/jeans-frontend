@extends('layout')
@section('content')
<div class="banner-area py-4 my-5 bg-light">
	<div class="row">
		<div class="col-xs-12 col-md-4 text-center">
			<div class="banner-single">
				<h3 class="fa fa-recycle"></h3>
				<h4 class="title">Free Return</h4>
				<p>30-Days Money back Grauntee</p>
			</div>
		</div>
		<div class="col-xs-12 col-md-4 text-center">
			<div class="banner-single">
				<h3 class="fa fa-mobile"></h3>
				<h4 class="title">Member Discount</h4>
				<p>30-Days Money back Grauntee</p>
			</div>
		</div>
		<div class="col-xs-12 col-md-4 text-center">
			<div class="banner-single last">
				<h3 class="fa fa-life-ring"></h3>
				<h4 class="title">Support 24/7</h4>
				<p>30-Days Money back Grauntee</p>
			</div>
		</div>
	</div>
</div>

<div class="product-area">
	<div class="row">
		<div class="col-md-8">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" href="#featured" role="tab" data-toggle="tab">Top Sales</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#buzz" role="tab" data-toggle="tab">Special</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#references" role="tab" data-toggle="tab">Most Liked</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane  active" id="featured">
					<div class="row">
						@if($result['top_seller']['success']==1)
							@foreach($result['top_seller']['product_data'] as $key=>$top_seller)
							<div class="col-xs-12 col-md-4">
								<div class="card product-card">
									<a href="{{ URL::to('/product-detail/'.$top_seller->products_slug)}}">
										<img class="card-img-top" src="{{asset('').$top_seller->products_image}}" alt="{{$top_seller->products_name}}">
									</a>

									<div class="card-body">
										<h5 class="card-title">{{$top_seller->products_name}}</h5>
<!--												<p><span class="line-clamp">game with Play station 4TM free and get a voucher discount.</span> </p>-->
										<table class="table">
											<tbody>
												<tr>
													<td align="left">
														<span class="price discounted">{{$web_setting[19]->value}}{{$top_seller->products_price}}</span>
													</td>
													<td align="right">
														<a href="" class="fa fa-heart active"></a>
													</td>
												</tr>
											</tbody>
										</table>
											<button  href="" class="btn btn-block btn-secondary cart" products_id="{{$top_seller->products_id}}">Add To Cart</button>
										
										<a href="{{ URL::to('/product-detail/'.$top_seller->products_slug)}}" class="btn btn-block btn-link">View</a>
									</div>
									<div class="product_added" @if(in_array($top_seller->products_id, $result['cartArray']))  style="display: block;" @endif>This div is only for addedd product.</div>
								</div>
							</div>
						@endforeach
					@endif
				  </div>
				</div>
				<div role="tabpanel" class="tab-pane " id="buzz">
					<div class="row">
					@if($result['special']['success']==1)
							@foreach($result['special']['product_data'] as $key=>$special)
							<div class="col-xs-12 col-md-4">
								<div class="card product-card">
									<a href="{{ URL::to('/product-ddetail/'.$special->products_slug)}}">
										<img class="card-img-top" src="{{asset('').$special->products_image}}" alt="{{$special->products_name}}">
									</a>

									<div class="card-body">
										<h5 class="card-title">{{$special->products_name}}</h5>
<!--												<p><span class="line-clamp">game with Play station 4TM free and get a voucher discount.</span> </p>-->
										<table class="table">
											<tbody>
												<tr>
													<td align="left">
														<span class="price line-through">{{$web_setting[19]->value}}{{$special->products_price}}</span>
														<span class="price discounted">{{$web_setting[19]->value}}{{$special->products_price}}</span>
													</td>
													<td align="right">
															<a href="" class="fa fa-heart active"></a>
													</td>
												</tr>
											</tbody>
										</table>
										<button  href="" class="btn btn-block btn-secondary cart" products_id="{{$special->products_id}}">Add To Cart</button>
										<a href="{{ URL::to('/product-ddetail/'.$special->products_slug)}}" class="btn btn-block btn-link">View</a>
									</div>
									<div class="product_added" @if(in_array($top_seller->products_id, $result['cartArray']))  style="display: block;" @endif>This div is only for addedd product.</div>
								</div>
							</div>
						@endforeach
					@endif
					</div>
				</div>
				<div role="tabpanel" class="tab-pane " id="references">
					<div class="row">
					@if($result['most_liked']['success']==1)
						@foreach($result['most_liked']['product_data'] as $key=>$most_liked)
							<div class="col-xs-12 col-md-4">
								<div class="card product-card">
									<a href="{{ URL::to('/product-detail/'.$most_liked->products_slug)}}">
										<img class="card-img-top" src="{{asset('').$most_liked->products_image}}" alt="{{$most_liked->products_name}}">
									</a>

									<div class="card-body">
										<h5 class="card-title">{{$most_liked->products_name}}</h5>
<!--												<p><span class="line-clamp">game with Play station 4TM free and get a voucher discount.</span> </p>-->
										<table class="table">
											<tbody>
												<tr>
													<td align="left">
															<span class="price discounted">{{$web_setting[19]->value}}{{$most_liked->products_price}}</span>
													</td>
													<td align="right">
															<a href="" class="fa fa-heart active"></a>
													</td>
												</tr>
											</tbody>
										</table>
										<button  href="" class="btn btn-block btn-secondary cart" products_id="{{$most_liked->products_id}}">Add To Cart</button>
										<a href="{{ URL::to('/productDetail/'.$most_liked->products_id)}}" class="btn btn-block btn-link">View</a>
										<div class="product_added" @if(in_array($top_seller->products_id, $result['cartArray']))  style="display: block;" @endif>This div is only for addedd product.</div>
									</div>
								</div>
							</div>
						@endforeach
					@endif
					</div>

				</div>
			</div>
		</div>
			<div class="col-md-4">
				@include('common.banners')
			</div>
	</div>
</div>


<div class="col-lg-3">
	<!-- @include('common.categories') -->
	
</div>			
		
@endsection 	


