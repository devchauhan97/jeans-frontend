@extends('layout')
@section('content')

<section class="site-content">
  <div class="container"> 
     <div class="group-banners en">
    	<div class="row">
        	<div class="col-12 col-sm-4">
            	<div class="banner-image">
                	<a title="Banner Image" href="{{ URL::to('/shop')}}"><img class="img-fluid" src="{{asset('').'images/banner_1_en.jpg'}}" alt="Banner Image"></a>
                </div>
            </div>
            <div class="col-12 col-sm-4">
            	<div class="banner-image">
                	<a title="Banner Image" href="{{ URL::to('/shop')}}"><img class="img-fluid" src="{{asset('').'images/banner_2_en.jpg'}}" alt="Banner Image"></a>
                </div>
            </div>
            <div class="col-12 col-sm-4">
            	<div class="banner-image">
                	<a title="Banner Image" href="{{ URL::to('/shop')}}"><img class="img-fluid" src="{{asset('').'images/banner_3_en.jpg'}}" alt="Banner Image"></a>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="group-banners ar">
    	<div class="row">
        	<div class="col-12 col-sm-4">
            	<div class="banner-image">
                	<a title="Banner Image" href="{{ URL::to('/shop')}}"><img class="img-fluid" src="{{asset('').'images/banner_1_ar.jpg'}}" alt="Banner Image"></a>
                </div>
            </div>
            <div class="col-12 col-sm-4">
            	<div class="banner-image">
                	<a title="Banner Image" href="{{ URL::to('/shop')}}"><img class="img-fluid" src="{{asset('').'images/banner_2_ar.jpg'}}" alt="Banner Image"></a>
                </div>
            </div>
            <div class="col-12 col-sm-4">
            	<div class="banner-image">
                	<a title="Banner Image" href="{{ URL::to('/shop')}}"><img class="img-fluid" src="{{asset('').'images/banner_3_ar.jpg'}}" alt="Banner Image"></a>
                </div>
            </div>
        </div>
    </div>
    <!-- dynamic content -->

    <div class="products-area">
      <div class="row">
        <div class="col-md-12">
          <div class="nav nav-pills" role="tablist">
             <a class="nav-link nav-item nav-index active" href="#featured" id="featured-tab" data-toggle="pill" role="tab" aria-controls="featured" aria-selected="true">@lang('website.TopSales')</a> 
            <a class="nav-link nav-item nav-index" href="#special" id="special-tab" data-toggle="pill" role="tab" aria-controls="special" aria-selected="false">@lang('website.Special')</a> 
             <a class="nav-link nav-item nav-index" href="#liked" id="liked-tab" data-toggle="pill" role="tab" aria-controls="liked" aria-selected="false">@lang('website.MostLiked')</a> 
          </div>
          
          <!-- Tab panes -->
          <div class="tab-content">
          	<div class="overlay" style="display:none;"><img src="{{asset('').'images/loader.gif'}}"></div>
            <div role="tabpanel" class="tab-pane fade show active" id="featured" role="tabpanel" aria-labelledby="featured-tab">
              <div id="owl_featured" class="owl-carousel owl_featured"> 
              @if($result['top_seller']['success']==1)
              	@foreach($result['top_seller']['product_data'] as $key=>$top_seller)
                
                <div class="product">
                  <article>
                  	
                       <div class="thumb"> <img class="img-fluid" src="{{asset('').$top_seller->products_image}}" alt="{{$top_seller->products_name}}"></div>
						<?php
                                $current_date = date("Y-m-d", strtotime("now"));
                                
                                $string = substr($top_seller->products_date_added, 0, strpos($top_seller->products_date_added, ' '));
                                $date=date_create($string);
                                date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                                
                                //echo $top_seller->products_date_added . "<br>";
                                $after_date = date_format($date,"Y-m-d");
                                
                                if($after_date>=$current_date){
                                    print '<span class="new-tag">';
                                    print __('website.New');
                                    print '</span>';
                                }
                                
                                if(!empty($top_seller->discount_price)){
                                    $discount_price = $top_seller->discount_price;	
                                    $orignal_price = $top_seller->products_price;	
                                    
                                    $discounted_price = $orignal_price-$discount_price;
                                    $discount_percentage = $discounted_price/$orignal_price*100;
                                    echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
                                }
                                 
                        ?>
                        <span class="tag text-center">
                        	<?=stripslashes($top_seller->categories_name)?>
                        </span>
                        <h2 class="title text-center">{{$top_seller->products_name}}</h2>
                        <div class="price text-center">
                        	@if(!empty($top_seller->discount_price))                        
                          	{{$web_setting[19]->value}}{{$top_seller->discount_price+0}}                           
                          	<span> {{$web_setting[19]->value}}{{$top_seller->products_price+0}}</span>                          
                          	@else
                          		{{$web_setting[19]->value}}{{$top_seller->products_price+0}} 
                          
                          	@endif 
						</div>
                          
                     
                     <div class="product-hover">
                     	<div class="icons">
                        	<div class="icon-liked">
                            	
                            	<span products_id = '{{$top_seller->products_id}}' class="fa @if ($top_seller->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">{{$top_seller->products_liked}}</span></span>
                            </div>
                            
                            <a href="{{ URL::to('/product-detail/'.$top_seller->products_slug)}}" class="fa fa-eye"></a>
                        </div>
                        <div class="buttons">
                        	@if(!in_array($top_seller->products_id,$result['cartArray']))
                        
                                <button  class="btn btn-block btn-secondary cart" products_id="{{$top_seller->products_id}}">@lang('website.Add to Cart')</button>
                                
                            @else
                                <button  class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                            @endif
                        </div>
                     </div>
                    
                  </article>
                </div>
                @endforeach
                    <div class="product last-product">
                      <article>
                      	<a href="{{ URL::to('/shop?type=topseller')}}" class="buttons">
                        	<span class="fa fa-angle-right"></span>
                        	<span class="btn btn-secondary">@lang('website.View All')</span>
                        </a>
                      	
                      </article>
                    </div>
                @endif 
                </div>
              <!-- 1st tab --> 
            </div>
            <div role="tabpanel" class="tab-pane fade" id="special" role="tabpanel" aria-labelledby="special-tab">
              <div id="owl_special" class="owl-carousel"> @if($result['special']['success']==1)
                @foreach($result['special']['product_data'] as $key=>$special)
                <div class="product">
                  <article>
                  
                  	<div class="thumb"><img class="img-fluid" src="{{asset('').$special->products_image}}" alt="{{$special->products_name}}"></div>
                    <?php
						$current_date = date("Y-m-d", strtotime("now"));
						
						$string = substr($special->products_date_added, 0, strpos($special->products_date_added, ' '));
						$date=date_create($string);
						date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
						
						//echo $top_seller->products_date_added . "<br>";
						$after_date = date_format($date,"Y-m-d");
						
						if($after_date>=$current_date){
							print '<span class="new-tag">';
							print __('website.New');
							print '</span>';
						}
						
						if(!empty($special->discount_price)){
							$discount_price = $special->discount_price;	
							$orignal_price = $special->products_price;	
							
							$discounted_price = $orignal_price-$discount_price;
							$discount_percentage = $discounted_price/$orignal_price*100;
							echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
						}
						 
			  		 ?>
                     <span class="tag text-center">
						<?=stripslashes($special->categories_name)?>
                    </span>
                    
                    <h2 class="title text-center">{{$special->products_name}}</h2>
                    
                    <div class="price text-center">                     
                    	{{$web_setting[19]->value}}{{$special->products_price+0}}                    
                    	<span>{{$web_setting[19]->value}}{{$special->discount_price+0}}</span>                        
                    </div>                   
                    
                    <div class="product-hover">
                     	<div class="icons">
                        	<div class="icon-liked">
                            	<span products_id = '{{$special->products_id}}' class="fa @if($special->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">{{$special->products_liked}}</span></span>
                            </div>
                            
                            <a href="{{ URL::to('/product-detail/'.$special->products_slug)}}" class="fa fa-eye"></a>
                        </div>
                        
                        
                        <div class="buttons">
                        	@if(!in_array($special->products_id,$result['cartArray']))
                        
                                <button  class="btn btn-block btn-secondary cart" products_id="{{$special->products_id}}">@lang('website.Add to Cart')</button>
                                
                            @else
                                <button  class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                            @endif
                        </div>
                     </div>
         
                  </article>
                </div>
                @endforeach
                    <div class="product last-product">
                      <article>
                      	<a href="{{ URL::to('/shop?type=special')}}" class="buttons">
                        	<span class="fa fa-angle-right"></span>
                        	<span class="btn btn-secondary">@lang('website.View All')</span>
                        </a>
                      </article>
                    </div>
                @endif
                </div>
            </div>
            
            <div role="tabpanel" class="tab-pane fade" id="liked" role="tabpanel" aria-labelledby="liked-tab">
              <div id="owl_liked" class="owl-carousel"> 
              @if($result['most_liked']['success']==1)
                @foreach($result['most_liked']['product_data'] as $key=>$most_liked)
                <div class="product">
                  <article>
                  	<div class="thumb"><img class="img-fluid" src="{{asset('').$most_liked->products_image}}" alt="{{$most_liked->products_name}}"></div>
                    <?php
						$current_date = date("Y-m-d", strtotime("now"));
						
						$string = substr($most_liked->products_date_added, 0, strpos($most_liked->products_date_added, ' '));
						$date=date_create($string);
						date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
						
						//echo $top_seller->products_date_added . "<br>";
						$after_date = date_format($date,"Y-m-d");
						
						if($after_date>=$current_date){
							print '<span class="new-tag">';
							print __('website.New');
							print '</span>';
						}
						
						if(!empty($most_liked->discount_price)){
							$discount_price = $most_liked->discount_price;	
							$orignal_price = $most_liked->products_price;	
							
							$discounted_price = $orignal_price-$discount_price;
							$discount_percentage = $discounted_price/$orignal_price*100;
							echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
						}
							 
				   ?>
                    <span class="tag text-center">
                    	<?=stripslashes($most_liked->categories_name)?>
                    </span>
                    
                    <h2 class="title text-center">{{$most_liked->products_name}}</h2>
                 
                    <div class="price text-center">
                      @if(!empty($most_liked->discount_price))
                      	{{$web_setting[19]->value}}{{$most_liked->discount_price+0}} <span>{{$web_setting[19]->value}}{{$most_liked->products_price+0}}</span> @else
                      	{{$web_setting[19]->value}}{{$most_liked->products_price+0}}
                      @endif 
                    </div>
                    
                    <div class="product-hover">
                     	<div class="icons">
                        	<div class="icon-liked">
                            	
                            	<span products_id = '{{$most_liked->products_id}}' class="fa @if($most_liked->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">{{$most_liked->products_liked}}</span></span>
                            </div>
                            
                            <a href="{{ URL::to('/product-detail/'.$most_liked->products_slug)}}" class="fa fa-eye"></a>
                        </div>
                        
                        <div class="buttons">
                        	@if(!in_array($most_liked->products_id,$result['cartArray']))
                                <button  class="btn btn-block btn-secondary cart" products_id="{{$most_liked->products_id}}">@lang('website.Add to Cart')</button> 
                            @else
                                <button  class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                            @endif
                        </div>
                     </div>
                              
					</article>
                </div>
                @endforeach
                    <div class="product last-product">
                      <article>
                      	<a href="{{ URL::to('/shop?type=mostliked')}}" class="buttons">
                        	<span class="fa fa-angle-right"></span>
                        	<span class="btn btn-secondary">@lang('website.View All')</span>
                        </a>
                      </article>
                    </div>
                @endif
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
   
    <!-- ./end of dynamic content --> 
  </div>
</section>

<section class="products-content"> @include('common.products') </section>




<section class="blog-content">
<div class="container">
    <div class="blog-area">
        <!-- heading -->
        <div class="heading">
            <h2>@lang('website.From our News') <small class="pull-right"><a href="{{ URL::to('/news')}}">@lang('website.View All')</a></small></h2>
            <hr>
        </div>
    
		<div class="row">
            <div class="blogs blogs-3x">
            	<!-- Blog Post -->
				@if($result['news']['success']==1)
					@foreach($result['news']['news_data'] as $key=>$news_data)
                        <div class="blog-post">
                            <article>
                                <div class="blog-thumb">
                                	<h4 class="blog-title">
                                        <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}">{{$news_data->news_name}} </a> 
                                        @if($news_data->is_feature==1)
                                            <span class="badge badge-success">@lang('website.Featured')</span>
                                        @endif
                                    </h4>
                                    <span class="blog-date">
                                        <strong>
                                            <?php
                                                $timestamp = strtotime($news_data->news_date_added);
                                                echo date('d',$timestamp);
                                            ?>
                                        </strong>
                                        <?php                                            
                                            echo date('M',$timestamp);
                                        ?>
                                    </span>
                                    
                                    <div class="blog-overlay">
                                        <a href="{{ URL::to('/news-detail/'.$news_data->news_slug)}}" class="fa fa-search-plus"></a>
                                    </div>
                                    
                                    <img class="img-fluid" src="{{asset('').$news_data->news_image}}" alt="">
                                </div>
                            </article>
                        </div>
                	@endforeach
            	@endif
			</div>
		</div>
    </div>
</div>
</section>
@endsection 