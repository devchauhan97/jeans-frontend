<footer>
	<div class="footer-top ptb-40">
		<div class="container">
			<div class="row">
				<div class="offset-md-3 col-md-6 text-center">
					<h3>News Letter</h3>
					<p>Join us to receive updates, access to exclusive deals, and more. No Spam, Promise!</p>
					<div class="input-group mb-3">
						<input type="text" class="form-control" id="new_letter_email" name="email" placeholder="Enter your email address..">
						<div class="input-group-append">
							<button class="btn btn-danger" type="button" onclick="getSubscription()"><i class="fa fa-location-arrow" aria-hidden="true"></i></button> 
						</div>
					</div>
				</div>
			</div>
			<div class="spacer-30"></div>
			<div class="row">
				<div class="col-md-2 col-sm-3 col-xs-6">
					<h4>Customer Support</h4>
					<div class="nav-widget">
						<ul>
							<li><a href="{{Url::to('/page?name=size-chart')}}">Size Chart</a></li>
							<li><a href="{{Url::to('/page?name=shipping-delivery')}}">Shipping & Delivery</a></li>
							<li><a href="{{Url::to('/page?name=refund-policy')}}">Return & Refund</a></li>
							<li><a href="{{Url::to('/page?name=privacy-policy')}}">Privacy Policy</a></li>
							<li><a href="{{Url::to('/page?name=term-services')}}">Team & Conditions</a></li>
						</ul>
					</div>
				</div>
				<div class="offset-md-1 col-md-2  col-sm-3 col-xs-6">
					<h4>Libaas</h4>
					<div class="nav-widget">
						<ul>
							<li><a href="{{Url::to('/page?name=about-us')}}">About Us</a></li>
							 
							<li><a href="{{Url::to('/contact-us')}}">Contact Us</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-6">
					<h4>Main Menu</h4>
					<div class="nav-widget ul-two-col">
						<ul>
							@foreach($result['commonContent']['categories'] as $categories_data) 
				            <li >
				              <a  href="{{ URL::to('/shop')}}?category={{$categories_data->categories_slug}}" >{{$categories_data->categories_description->categories_name}}</a>
				                
				             </li>
				            @endforeach
						</ul>
					</div>
				</div>
				<div class="offset-md-1 col-md-3 col-sm-3 col-xs-6">
					<h4>Information</h4>
					<!-- <span class="location-icon">{{$result['commonContent']['setting'][4]->value}} ,{{$result['commonContent']['setting'][5]->value}},{{$result['commonContent']['setting'][6]->value}} ,{{$result['commonContent']['setting'][8]->value}} </span> -->
					<span class="phone-icon">{{$result['commonContent']['setting'][11]->value}}</span>
					<span class="mail-icon">{{$result['commonContent']['setting'][3]->value}}</span>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<p>Copyright© 2019. libaas.xyz</p>
				</div>
				<div class="col-md-4 text-center">
					<ul class="footer-social">
						<li><a href="{{$result['commonContent']['setting'][50]->value}}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
						<li><a href="{{$result['commonContent']['setting'][52]->value}}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						<li><a href="{{$result['commonContent']['setting'][53]->value}}"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
						<li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
						<li><a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
						<li><a href="#"><i class="fa fa-camera-retro" aria-hidden="true"></i></a></li>
					</ul>
				</div>
				<div class="col-md-4 text-right">
					<ul class="footer-social">
						<li><a href="#"><img src="{!! asset('images/pay1.png') !!}"></a></li>
						<li><a href="#"><img src="{!! asset('images/pay2.png') !!}"></a></li>
						<li><a href="#"><img src="{!! asset('images/pay3.png') !!}"></a></li>
						<li><a href="#"><img src="{!! asset('images/pay4.png') !!}"></a></li>
						<li><a href="#"><img src="{!! asset('images/pay5.png') !!}"></a></li>
						<li><a href="#"><img src="{!! asset('images/pay6.png') !!}"></a></li>
						<li><a href="#"><img src="{!! asset('images/pay7.png') !!}"></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>
<script src="{!! asset('js/jquery-ui.min-1.12.1.js') !!}"></script>
<script src="{!! asset('bootstrap/js/bootstrap.min.js') !!}"></script> <!-- Bootstrap javascript functions -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
<script src="{!! asset('js/slick.min.js') !!}"></script>
@include('common.scripts')
