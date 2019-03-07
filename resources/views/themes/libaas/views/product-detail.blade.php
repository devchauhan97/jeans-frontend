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
<!-- Site Content -->

<style type="text/css">
    
.line-through{
    
    text-decoration: line-through;
}
</style>
<section class="page-header" style="height: 120px;">
</section>

<section class="content main-container home-detail pb-0" id="site-content">
    <div class="container">
        <div class="row">
            <div class="offset-md-0 col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ URL::to('/')}}">Home</a></li>
                    <!-- <li><a href="{{ URL::to($result['detail']['product_data'][0]->categories_slug)}}">{{$result['detail']['product_data'][0]->categories_name}}</a></li> -->
                     <li>{{$result['detail']['product_data'][0]->products_name}}</li>
                     
                </ul>
            </div>
        </div>
        <div class="row product-detail">
            <div class=" col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-gallery">
                            <!--product gallery-->
                            <div class="content-carousel">
                                <div class="owl-carousel owl-product">

                                    <div><img class="box-border bottom-box-shadow" src="{{getFtpImage($result['detail']['product_data'][0]->products_image) }}" alt="Text" data-gc-caption="" /></div>
                                        @foreach( $result['product_images'] as $key=>$images ) 
                                        <div>
                                            <img class="box-border bottom-box-shadow" src="{{getFtpImage($images->image) }}" alt="Text" />
                                        </div>
                                        @endforeach
                                </div>
                              </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="product-detail-in">
                            <h3 style="line-height:40px;">   {{$result['detail']['product_data'][0]->products_name}}</h3>
                                <span>{{$result['detail']['product_data'][0]->sort_description}}  </span>
                                <p>RIF : {{$result['detail']['product_data'][0]->products_model}}</p>
                                @if(!empty($result['detail']['product_data'][0]->discount_price))
                                    <h3>
                                        {{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->discount_price+0}} 
                                    </h3>
                                @endif 
                                <h3 class="@if(!empty($result['detail']['product_data'][0]->discount_price)) line-through @else change_price @endif">{{$web_setting[19]->value}}{{$result['detail']['product_data'][0]->products_price+$result['attributes_price']}}</h3>

                                <div class="detail-action">
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
                                        <div class="qty">
                                            @if( $result['detail']['product_data'][0]->semi_stitched)
                                            <a class="btn" >semi stitched</a>
                                            @endif
                                            <div class="qty-in">
                                                <span class="fa fa-minus qtyminus"></span>
                                                <input type="text" readonly name="quantity" value="1" min="1" max="{{ $result['detail']['product_data'][0]->products_quantity}}" class="form-control qty">
                                                <span class="fa fa-plus qtyplus"></span>
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
                                        <div class="action-btn">
                                            <a  class="btn btn-primary btn-dark buy-now">Buy Now</a>
                                            @if($result['detail']['product_data'][0]->products_quantity == 0)
                                                <a class="btn btn-primary btn-dark " type="button">@lang('website.Out of Stock')</a>
                                            @else
                                                <a class="btn btn-primary btn-dark add-to-Cart" type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i>@lang('website.Add to Cart')</a>
                                            @endif

                                            <a class="btn btn-primary is_liked" products_id="{{$result['detail']['product_data'][0]->products_id}}" ><i  class="fa @if($result['isLiked']==1) fa-heart @else fa-heart-o @endif" aria-hidden="true"></i>Wishlist</a>

                                        </div>
                                    </form>
                                </div>
                                <div class="shipping-policy">
                                    <ul>
                                        <li><a ><img src="{!! asset('images/icon1.png') !!}">Delivery in 3 Business Days</a></li>
                                        <li><a  ><img src="{!! asset('images/icon2.png') !!}">Free Express Shipping</a></li>
                                        <li><a  ><img src="{!! asset('images/icon3.png') !!}">Easy Return</a></li>
                                    </ul>
                                </div>
                                <div class="clear"></div>
                                <div class="detail-tab">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                          <a class="nav-item nav-link active" id="nav-des-tab" data-toggle="tab" href="#nav-des" role="tab" aria-controls="nav-des" aria-selected="true">Description                                     </a>
                                          <a class="nav-item nav-link" id="nav-feature-tab" data-toggle="tab" href="#nav-feature" role="tab" aria-controls="nav-feature" aria-selected="false">Features</a>
                                          <a class="nav-item nav-link" id="nav-size-tab" data-toggle="tab" href="#nav-size" role="tab" aria-controls="nav-size" aria-selected="false">Size</a>
                                          <a class="nav-item nav-link" id="nav-wash-tab" data-toggle="tab" href="#nav-wash" role="tab" aria-controls="nav-wash" aria-selected="false">Wash Care</a>
                                        </div>
                                      </nav>
                                      <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-des" role="tabpanel" aria-labelledby="nav-des-tab"><?=stripslashes($result['detail']['product_data'][0]->products_description)?></div>
                                        <div class="tab-pane fade" id="nav-feature" role="tabpanel" aria-labelledby="nav-feature-tab"><?=stripslashes($result['detail']['product_data'][0]->features)?></div>
                                        <div class="tab-pane fade" id="nav-size" role="tabpanel" aria-labelledby="nav-size-tab"><?=stripslashes($result['detail']['product_data'][0]->size)?> </div>
                                        <div class="tab-pane fade" id="nav-wash" role="tabpanel" aria-labelledby="nav-wash-tab"><?=stripslashes($result['detail']['product_data'][0]->wash_care)?></div>
                                      </div>
                                </div>
                                <div class="detail-share">
                                    <ul>
                                        <li>Share:- </li>
                                        <li><a  ><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                        <li><a  ><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                        <li><a  ><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                        <li><a  ><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                                        <li><a  ><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                                    </ul>
                                </div>
                                <!-- <p>A scintillating silver lehenga with sparkling stone work in delicate florets across the neck and U shaped back. Flattering floral clusters of stone, grace the lehenga and woven diagonals adorn the sleeves.</p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="main-container ptb-40">
        @if( count($result['simliar_products']['product_data']) )
        <div class="container text-center mtb-40 mt-0">
            <h3 class="">Similar Products</h3>
        </div>
        <div class="container">
            <div class="owl-carousel owl-four owl-theme ">
            @foreach($result['simliar_products']['product_data'] as $key=>$products)

                @if($result['detail']['product_data'][0]->products_id != $products->products_id)

                @if(++$key<=5)
                <div class="item">
                    <div class="product-box box-border bottom-box-shadow">
                        <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">
                            <img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}">
                        </a>
                        <?php
                            $current_date = date("Y-m-d", strtotime("now"));
                            
                            $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
                            $date=date_create($string);
                            date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                            
                            
                            $after_date = date_format($date,"Y-m-d");
                            
                            if($after_date>=$current_date){
                                print '<div class="badge">N<br>E<br>W</div>';
                            }
                            
                            if(!empty($products->discount_price)){
                                $discount_price = $products->discount_price;    
                                $orignal_price = $products->products_price; 
                                
                                $discounted_price = $orignal_price-$discount_price;
                                $discount_percentage = $discounted_price/$orignal_price*100;
                                echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
                            }
                        ?>

                        
                        <div class="product-action">
                            <div class="action">
                                <ul>
                                    <li><a class="is_liked" products_id="{{$products->products_id}}"><i   class="fa @if( $products->isLiked == 1 ) fa-heart @else fa-heart-o @endif "  aria-hidden="true"></i></a></li>
                                    <li><a  ><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                                    <li><a href="{{$products->products_slug}}"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                            <div class="clear"></div>
                            <div class="action-title">
                                <p>
                                    {{$products->products_name}}
                                </p>
                                @if(!empty($products->discount_price))
                                <strong>
                                    {{$web_setting[19]->value}}{{$products->discount_price+0}}
                                </strong>
                                <strong class="line-through"> 
                                     {{$web_setting[19]->value}}{{$products->products_price+0}} 
                                 </strong>
                                @else
                                <strong> 
                                    {{$web_setting[19]->value}}{{$products->products_price+0}}
                                </strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                 @endif      
                @endif
            @endforeach
            </div>
        </div>
        @endif
        @if(count($result['most_liked_products']))
        <div class="spacer-40"></div>
        <div class="container text-center mtb-40 mt-0">
            <h3 class="">Most liked</h3>
        </div>
        <div class="container">
            <div class="owl-carousel owl-four owl-theme ">
            @foreach($result['most_liked_products'] as $key=>$products)
                <div class="item">
                    <div class="product-box box-border bottom-box-shadow">
                        <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">
                            <img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}">
                        </a>
                        <?php

                            $current_date = date("Y-m-d", strtotime("now"));
                            
                            $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
                            $date=date_create($string);
                            date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                            
                            
                            $after_date = date_format($date,"Y-m-d");
                            
                            if($after_date>=$current_date){
                                print '<div class="badge">N<br>E<br>W</div>';
                            }
                            
                            if(!empty($products->discount_price)){
                                $discount_price = $products->discount_price;    
                                $orignal_price = $products->products_price; 
                                
                                $discounted_price = $orignal_price-$discount_price;
                                $discount_percentage = $discounted_price/$orignal_price*100;
                                echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
                            }
                        ?>
                        <div class="product-action">
                            <div class="action">
                                <ul>
                                    <li><a class="is_liked" products_id="{{$products->products_id}}"><i   class="fa @if( $products->isLiked == 1 ) fa-heart @else fa-heart-o @endif "  aria-hidden="true"></i></a></li>
                                    <li><a  ><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                                    <li><a href="{{$products->products_slug}}"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                            <div class="clear"></div>
                            <div class="action-title">
                                <p>
                                    {{$products->products_name}}
                                </p>
                                @if(!empty($products->discount_price))
                                <strong>
                                    {{$web_setting[19]->value}}{{$products->discount_price+0}}
                                </strong>
                                <strong class="line-through"> 
                                     {{$web_setting[19]->value}}{{$products->products_price+0}} 
                                 </strong>
                                @else
                                <strong> 
                                    {{$web_setting[19]->value}}{{$products->products_price+0}}
                                </strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        @endif
        @if( count($result['recently_viewed']) )
        <div class="spacer-40"></div>
        <div class="container text-center mtb-40 mt-0">
            <h3 class="">Recently Viewed</h3>
        </div>
        <div class="container">
            <div class="owl-carousel owl-four owl-theme ">
                @foreach($result['recently_viewed'] as $key=>$products)
                  <div class="item">
                    <div class="product-box box-border bottom-box-shadow">
                        <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">
                            <img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}">
                        </a>
                        <?php
                            $current_date = date("Y-m-d", strtotime("now"));
                            
                            $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
                            $date=date_create($string);
                            date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                            
                            
                            $after_date = date_format($date,"Y-m-d");
                            
                            if($after_date>=$current_date){
                                print '<div class="badge">N<br>E<br>W</div>';
                            }
                            
                            if(!empty($products->discount_price)){
                                $discount_price = $products->discount_price;    
                                $orignal_price = $products->products_price; 
                                
                                $discounted_price = $orignal_price-$discount_price;
                                $discount_percentage = $discounted_price/$orignal_price*100;
                                echo "<span class='discount-tag'>".(int)$discount_percentage."%</span>";
                            }
                        ?>
                        <div class="product-action">
                            <div class="action">
                                <ul>
                                    <li><a class="is_liked" products_id="{{$products->products_id}}"><i   class="fa @if( $products->liked_customers_id  ) fa-heart @else fa-heart-o @endif "  aria-hidden="true"></i></a></li>
                                    <li><a  ><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                                    <li><a href="{{$products->products_slug}}"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                            <div class="clear"></div>
                            <div class="action-title">
                                <p>
                                    {{$products->products_name}}
                                </p>
                                @if(!empty($products->discount_price))
                                <strong>
                                    {{$web_setting[19]->value}}{{$products->discount_price+0}}
                                </strong>
                                <strong class="line-through"> 
                                     {{$web_setting[19]->value}}{{$products->products_price+0}} 
                                 </strong>
                                @else
                                <strong> 
                                    {{$web_setting[19]->value}}{{$products->products_price+0}}
                                </strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
<script>
jQuery(document).ready(function(){

    jQuery(".owl-product").owlCarousel({    
        loop:true,
        items:1,
        margin:0,
        stagePadding: 0,
        autoplay:false  
    });

    dotcount = 1;

    jQuery('.owl-dot').each(function() {
        jQuery( this ).addClass( 'dotnumber' + dotcount);
        jQuery( this ).attr('data-info', dotcount);
        dotcount=dotcount+1;
    });

    slidecount = 1;

    jQuery('.owl-item').not('.cloned').each(function() {
        jQuery( this ).addClass( 'slidenumber' + slidecount);
        slidecount=slidecount+1;
    });

    jQuery('.owl-dot').each(function() {    
        grab = jQuery(this).data('info');       
        slidegrab = jQuery('.slidenumber'+ grab +' img').attr('src');
        jQuery(this).css("background-image", "url("+slidegrab+")");     
    });

    amount = jQuery('.owl-dot').length;
    gotowidth = 100/amount;  
           
    jQuery('.owl-dot').css("height", gotowidth+"%");

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
    jQuery( ".owl-prev").html('<img src="{!! asset("images/slider-left-arrow.png") !!}">');
    jQuery( ".owl-next").html('<img src="{!! asset("images/slider-right-arrow.png") !!}">');
});
//add-to-Cart with custom options
jQuery(document).on('click', '.buy-now, .add-to-Cart', function(e){  

    var $this = jQuery(this);
    jQuery('#loader').css('display','flex');
    var formData = jQuery("#add-Product-form").serialize();
    var url = jQuery('#checkout_url').val();
    var message;

    jQuery.ajax({
        url: '{{ URL::to("/add/cart")}}',
        type: "POST",
        data: formData,
        success: function (res) {
            if(res.trim() == "already added") {
                //notification
                message = 'Product is added!';
            } else {

                window.location.href='{{ URL::to("/viewcart")}}';
                if ($this.hasClass('buy-now')){
                    window.location.href='{{ URL::to("/checkout")}}';
                }else{
                    jQuery('.head-cart-content').html(res);
                    jQuery(parent).addClass('active');
                }
                message = 'Product is added!';
            }
            if(url.trim()=='true') {
                window.location.href = '{{ URL::to("/checkout")}}';
            } else {
                jQuery('#loader').css('display','none');
                //window.location.href = '';
                //message = "@lang('website.Product is added')";            
                //notification(message);
            }
        },
        error: function (reject,exception) {
                jQuery('#loader').css('display','none');
                notification( reject.responseJSON );
                
        }
    });
});
    
// This button will increment the value
jQuery('.qtyplus').click(function(e){
    // Stop acting like a button
    e.preventDefault();
    // Get the field name
    fieldName = jQuery(this).attr('field');
    // Get its current value
    var currentVal = parseInt(jQuery(this).prev('.qty').val());
    // If is not undefined
    if (!isNaN(currentVal)) {
        @if(!empty($result['detail']['product_data'][0]->products_quantity))                
            if(currentVal < {{ $result['detail']['product_data'][0]->products_quantity}} ){
                // Increment
                jQuery(this).prev('.qty').val(currentVal + 1);                  
            }
            if(currentVal == {{ $result['detail']['product_data'][0]->products_quantity}} ){
                // Increment
                notification('Product quantity limit is exceeded than  available limit.');              
            }               
        @endif

    } else {
        // Otherwise put a 0 there
        jQuery(this).prev('.qty').val(0);
    }
    
    var qty = jQuery('[name=quantity]').val();
    var products_price = jQuery('#products_price').val();
    var total_price = parseFloat(qty) * parseFloat(products_price); 
     
    jQuery('.total_price').html('<?=$web_setting[19]->value?>'+total_price.toFixed(2));
});

// This button will decrement the value till 0
jQuery(".qtyminus").click(function(e) {
    
    // Stop acting like a button
    e.preventDefault();
    
    // Get the field name
    fieldName = jQuery(this).attr('field');

    // Get its current value
    var currentVal = parseInt(jQuery(this).next('.qty').val());
    // If it isn't undefined or its greater than 0
    if (!isNaN(currentVal) && currentVal > 1) {
        // Decrement one
        jQuery(this).next('.qty').val(currentVal - 1);
    } else {
        
        // Otherwise put a 0 there
        jQuery(this).next('.qty').val(1);

    }
    
    var qty = jQuery('[name=quantity]').val();
    var products_price = jQuery('#products_price').val();
    var total_price = parseFloat(qty) * parseFloat(products_price); 

    jQuery('.total_price').html('<?=$web_setting[19]->value?>'+total_price.toFixed(2));

});
</script>
@endsection