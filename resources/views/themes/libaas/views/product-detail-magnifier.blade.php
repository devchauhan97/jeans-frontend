@extends('layouts')
@section('customcss')

@if(!empty(session("theme")))
    <link href="{!! asset('css/'.session("theme").'.css') !!} " media="all" rel="stylesheet" type="text/css"/>
@else
    <link href="{!! asset('css/app.css') !!} " media="all" rel="stylesheet" type="text/css"/>
@endif
<link rel="stylesheet" type="text/css" href="{!! asset('css/bootstrap.min.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('css/style.min.css') !!}"> 
<!-- <link href="{!! asset('css/responsive.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/rtl.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/font-awesome.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/owl.carousel.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/bootstrap-select.css') !!} " media="all" rel="stylesheet" type="text/css"/> -->
<script src="{!! asset('glass/glass.js') !!}"></script> 
<link rel="stylesheet" href="{!! asset('glass/glass.css') !!}"/>
 
@endsection
@section('content')
<section class="site-content">
    <div class="container">
        <div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>{{$result['detail']['product_data'][0]->products_name}}</h3>
                <ol class="breadcrumb">
                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    
                    @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                        <li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['sub_category_slug'])}}">{{$result['sub_category_name']}}</a></li>
                    @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                        <li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
                    @endif
                    
                    <li class="breadcrumb-item active">{{$result['detail']['product_data'][0]->products_name}}</li>
                </ol>
            </div>
        </div>

        <div class="product-detail-area">
            <div class="row">
                <div class="col-12">
                    <div class="detail-area">
                        <div class="row">
                            <div class="col-12 col-lg-5">
                                <div id=" " class="carousel slide">

                                     <ul id="glasscase" class="gc-start">
                                        <li><img src="{{getFtpImage($result['detail']['product_data'][0]->products_image) }}" alt="Text" data-gc-caption="" /></li>
                                        @foreach( $result['product_images'] as $key=>$images ) 
                                        <li>
                                            <img src="{{getFtpImage($images->image) }}" alt="Text" />
                                        </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
            
                            <div class="col-12 col-lg-7">
                                <div class="product-summary">
                                    <div class="like-box">
                                        <span products_id='{{$result['detail']['product_data'][0]->products_id}}' class="fa @if($result['isLiked']==1) fa-heart @else fa-heart-o @endif is_liked">
                                            <span class="badge badge-secondary">{{$result['detail']['product_data'][0]->products_liked}}</span>
                                        </span>                                          
                                    </div>                                    
                                    <h3 class="product-title">{{$result['detail']['product_data'][0]->products_name}}</h3> 
                                   <div class="star-ratings">
                                        <i class="fa fa-star aria-hidden="true"></i>

                                        <i class="fa fa-star aria-hidden="true"></i>

                                        <i class="fa fa-star aria-hidden="true"></i>
                                        <a href="#" class="rating">393 Ratings</a> & <a href="#" class="reviews">262 Reviews</a>

                                    </div>
                                    <div class="product-info">
                                        
                                        @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                                            
                                         <a href="{{ URL::to('/shop?category='.$result['sub_category_slug'])}}" class="category">{{$result['sub_category_name']}}</a>
                                        @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                                         <a href="{{ URL::to('/shop?category='.$result['category_slug'])}}" class="category">{{$result['category_name']}}</a>
                                            
                                        @endif
                                        
                                        <div class="orders">{{$result['detail']['product_data'][0]->products_ordered}}&nbsp;@lang('website.Order(s)')</div>                                        @if($result['detail']['product_data'][0]->products_quantity == 0)
                                            <div class="availbility"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.Out of Stock') </div>
                                        @elseif($result['detail']['product_data'][0]->products_quantity <= $result['detail']['product_data'][0]->low_limit )
                                            <div class="availbility"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.Low in Stock') </div>
                                        @else 
                                            <div class="availbility"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.In stock') </div>
                                        @endif
                                    </div>
                                    <div class="select-size">

                                        <h4>select size</h4>

                                        <span class="size-chart" data-toggle="modal" data-target="#size_chart">Size Chart</span>
                                        <div class="modal fade" id="size_chart"  role="dialog">
                                              <div class="modal-dialog modal-lg"">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h4 class="modal-title">Size Chart</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                  </div>

                                                  <div class="modal-body">
                                                    <img src="{!! asset('images/sizechart.png') !!}" alt="sizechart" />
                                                  </div>
                                                </div>
                                            </div> 
                                        </div>                   

                                    </div>
                                    <div class="product-price">
                                        @if(!empty($result['detail']['product_data'][0]->discount_price))
                                            <span class="discount">
                                                    {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->discount_price+0}} 
                                            </span>
                                        @endif      
                                        <!--discount_price-->
                                        <span class="price @if(!empty($result['detail']['product_data'][0]->discount_price)) line-through @else change_price @endif" >
                                            {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->products_price+$result['attributes_price']}}
                                        </span>                                    
                                    </div>
            
                                    <form name="attributes" id="add-Product-form" method="post" >
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                                        <input type="hidden" name="products_id" value="{{$result['detail']['product_data'][0]->products_id}}">
                                        <input type="hidden" name="products_price" id="products_price" value="@if(!empty($result['detail']['product_data'][0]->discount_price)){{$result['detail']['product_data'][0]->discount_price+$result['attributes_price']}}@else{{$result['detail']['product_data'][0]->products_price+$result['attributes_price']}}@endif">
                                        
                                        <input type="hidden" name="checkout" id="checkout_url" value="@if(!empty(app('request')->input('checkout'))) {{ app('request')->input('checkout') }} @else false @endif" >  
                                        
                                        @if(count($result['attributes'])>0)                                            
                                            <div class="form-row">  
                                                @foreach( $result['attributes'] as $attributes_data )                     
                                                <input type="hidden" name="option_name[]" value="{{ $attributes_data['option']['name'] }}" >
                                                <input type="hidden" name="option_id[]" value="{{ $attributes_data['option']['id'] }}" >
                                                <input type="hidden" name="{{ $attributes_data['option']['name'] }}" id="{{ $attributes_data['option']['name'] }}" value="0" >                              
                                                <div class="form-group col-sm-4">                           
                                                    <label for="{{ $attributes_data['option']['name'] }}" class="col-sm-12 col-form-label">{{ $attributes_data['option']['name'] }}</label>     
                                                    <div class="col-sm-12">                             
                                                        <select name="{{ $attributes_data['option']['id'] }}"  class="form-control {{ $attributes_data['option']['name'] }}">
                                                          <?php
                                                          $name =$attributes_data['option']['name'];
                                                          ?>
                                                          <option  selected disabled>Select {{ $name }}</option>
                                                            @foreach( $attributes_data['values'] as $values_data )

                                                            <option value="{{ $values_data['id'] }}" prefix = '{{ $values_data['price_prefix'] }}'  value_price ="{{ $values_data['price']+0 }}" @if(Request()->{$name}  == $values_data['id'] ) selected   @endIf>{{ $values_data['value'] }}</option>                             
                                                            @endforeach                             
                                                        </select>                               
                                                    </div>                          
                                                </div>
                                                @endforeach                         
                                            </div>
                                        @endif  
                                        
                                        <div class="form-inline product-box">
                                            <div class="form-group Qty">

                                                <label for="quantity" class="col-form-label">@lang('website.Quantity') </label>
                        
                                                <div class="input-group">                       
                                                    <span class="input-group-btn first qtyminus">    
                                                        <button class="btn btn-defualt" type="button">-</button>                        
                                                    </span>                     
                                                    <input type="text" readonly name="quantity" value="1" min="1" max="{{ $result['detail']['product_data'][0]->products_quantity}}" class="form-control qty">                      
                                                    <span class="input-group-btn last qtyplus">                     
                                                        <button class="btn btn-defualt" type="button">+</button>                        
                                                    </span>                     
                                                </div>
                                            </div>
                                            
                                            <div class="price-box">
                                                <span>@lang('website.Total Price')&nbsp;:</span>
                                                <span class="total_price">
                                                  @if(!empty($result['detail']['product_data'][0]->discount_price))
                                                    {{$web_setting[19]->value}}{{number_format($result['detail']['product_data'][0]->discount_price+$result['attributes_price'],2)}}
                                                @else
                                                {{$web_setting[19]->value}}{{number_format($result['detail']['product_data'][0]->products_price+$result['attributes_price'],2)}}@endif
                                                </span>             
                                            </div>  
                                            <div class="buttons">
                                                @if($result['detail']['product_data'][0]->products_quantity == 0)
                                                    <button class="btn btn-danger btn-round " type="button">@lang('website.Out of Stock')</button>
                                                @else
                                                    <button class="btn btn-secondary btn-round add-to-Cart" type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Add to Cart')
                                                    </button>
                                                @endif 
                                            </div>   
                                        </div>
                                    </form> 
                                </div>  
                            </div>
                            
                            <div class="col-12">
                                <div class="product-tabs">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-pills" id="myTab" role="tablist">
                                      <li class="nav-item">
                                        <a class="nav-link active" id="product-desc-tab" data-toggle="tab" href="#product_desc" role="tab" aria-controls="product_desc" aria-selected="true">@lang('website.Products Description')</a>
                                      </li>
                                      
                                    </ul>
                                    
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="product_desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                            <p class="product-description"><?=stripslashes($result['detail']['product_data'][0]->products_description)?></p>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        <div class="related-product-area">
            <div class="heading">
                    <h2>@lang('website.Related Products') <small class="pull-right"><a href="{{ URL::to('/shop?category_id='.$result['detail']['product_data'][0]->categories_id)}}">@lang('website.View All')</a></small></h2>
                    <hr>
                </div>
            <div class="row">
                
                
                <div class="products products-4x">
                    @foreach($result['simliar_products']['product_data'] as $key=>$products)

                    @if($result['detail']['product_data'][0]->products_id != $products->products_id)
    
                    @if(++$key<=5)
    
                    <div class="product">
                        <article>
                            <div class="thumb"><img class="img-fluid" src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}"></div>
                            <?php
                                $current_date = date("Y-m-d", strtotime("now"));
                                
                                $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
                                $date=date_create($string);
                                date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                                
                                
                                $after_date = date_format($date,"Y-m-d");
                                
                                if($after_date>=$current_date){
                                    print '<span class="new-tag">New</span>';
                                }
                                
                                if(!empty($products->discount_price)){
                                    $discount_price = $products->discount_price;    
                                    $orignal_price = $products->products_price; 
                                    
                                    $discounted_price = $orignal_price-$discount_price;
                                    $discount_percentage = $discounted_price/$orignal_price*100;
                                    echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
                                }
                            ?>
                            
                            <span class="tag text-center">
                                <?=stripslashes($products->categories_name)?>
                            </span>
                            
                            <h2 class="title text-center">{{$products->products_name}}</h2>
                            <!--<p class="like"> <span href="#" products_id = '{{$products->products_id}}' class="fa @if($products->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"></span> <span>{{$products->products_liked}} @lang('website.Likes')</span></p>-->
                                
                            <div class="price text-center">
                                @if(!empty($products->discount_price))
                                    {{$web_setting[19]->value}}{{$products->discount_price+0}}
                                    <span>{{$web_setting[19]->value}}{{$products->products_price+0}}</span> 
                                @else
                                    {{$web_setting[19]->value}}{{$products->products_price+0}}
                                @endif
                            </div>
                            
                            <!--@if(!in_array($products->products_id,$result['cartArray']))
                                <button type="button" class="btn btn-cart cart" products_id="{{$products->products_id}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                            @else
                                <button type="button"  class="btn btn-cart acitve"><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                            @endif-->
                            
                            <div class="product-hover">
                                <div class="icons">
                                    <div class="icon-liked">
                                        <span products_id = '{{$products->products_id}}' class="fa @if($products->isLiked==1) fa-heart @else fa-heart-o @endif is_liked"><span class="badge badge-secondary">{{$products->products_liked}}</span></span>
                                    </div>
                                    <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="fa fa-eye"></a>
                                </div>
                                
                                <div class="buttons">
                                    @if(!in_array($products->products_id,$result['cartArray']))
                                
                                        <button  class="btn btn-block btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                        
                                    @else
                                        <button  class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                                    @endif
                                </div>
                                
                             </div>
                        </article>
                      </div>
    
                    @endif      
    
                    @endif
    
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
     $(document).ready( function () {
            //If your <ul> has the id "glasscase"
            $('#glasscase').glassCase({ 'thumbsPosition': 'bottom', 'widthDisplay' : 560});
        });
</script>
@endsection



