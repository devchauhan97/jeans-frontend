<section class="hero_slider">
	  <div id="myCarousel" class="carousel slide" data-ride="carousel">
	    <!-- Indicators -->
	    <ol class="carousel-indicators">
	      @foreach($result['slides'] as $key=>$slides_data)
			<li data-target="#myCarousel" data-slide-to="{{ $key }}" class="@if($key==0) active @endif"></li>
		@endforeach
	    </ol>

	    <!-- Wrapper for slides -->
	    <div class="carousel-inner" role="listbox">	    	
	     
	     @foreach($result['slides'] as $key=>$slides_data)
	      <div class="item  @if($key==0) active @endif">
	      	@if($slides_data->type == 'category')
				<a href="{{ URL::to('/shop?category='.$slides_data->url)}}">
			@elseif($slides_data->type == 'product')
				<a href="{{ URL::to('/product-detail/'.$slides_data->url)}}">
			@elseif($slides_data->type == 'mostliked')
				<a href="{{ URL::to('shop?type=mostliked')}}">
			@elseif($slides_data->type == 'topseller')
				<a href="{{ URL::to('shop?type=topseller')}}">
			@elseif($slides_data->type == 'deals')
				<a href="{{ URL::to('shop?type=deals')}}">
			@endif
	        <img src="{{getFtpImage($slides_data->image)}}" alt="Chicago">
	    </a>
	       <!--  <div class="slider-content">
	        	
	        	<a class="shop-now-button" href="{{ URL::to('/shop')}}">Shop Now</a>
	        </div> -->
	      </div>
	 @endforeach
	    
	    </div>

	    <!-- Left and right controls -->
	    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
	      <span class="glyphicon glyphicon-chevron-left"><img src="{{asset('').'images/left_slide_icon.png'}}"></span>
	      <span class="sr-only">Previous</span>
	    </a>
	    <a class="right carousel-control" href="#myCarousel" data-slide="next">
	      <span class="glyphicon glyphicon-chevron-right"><img src="{{asset('').'images/right_slide_icon.png'}}"></span>
	      <span class="sr-only">Next</span>
	    </a>
	  </div>
	</section>