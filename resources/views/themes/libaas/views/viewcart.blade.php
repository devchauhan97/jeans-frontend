@extends('layouts')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> 

<section class="page-header" style="height: 120px;">
</section>
<!-- Site Content -->
<section class="content main-container category-bg" id="site-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8 white-bg">
                <ul class="breadcrumb">
                    <li><a href="{{URL::to('/')}}">Home</a></li>
                    <li>@lang('website.Cart')</li>
                </ul>
                @if(count($result['cart']) > 0)
                <h4>My Shopping Bag – {{count($result['cart'])}} items </h4>

                <div class="cart-box">
                    <div class="cart-title">
                        <img src="images/icon2.png"><h4>@lang('website.Free Express Shipping')</h4>
                    </div>
                    @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                         {{ session()->get('message') }}
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>               
                    @endif
                    @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                         {{ session()->get('error') }}
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>               
                    @endif
                    <?php 
                        $price = 0;
                    ?>
                    <form method='POST' id="update_cart_form" action='{{ URL::to('/update/cart')}}' >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        
                        <div class="cart-product">

                            @foreach( $result['cart'] as $products)
                            <?php 
                                $price+= $products->final_price * $products->customers_basket_quantity;
                                $image=$products->image;
                            
                                if( isset($products->customers_basket_attributes->image) )
                                {
                                   $image = $products->customers_basket_attributes->image;
                                }     
                            ?>
                            <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                            <input name="quantity[]" type="hidden" readonly value="{{$products->customers_basket_quantity}}" id="qty_{{$products->customers_basket_id}}" > 
                            <div class="row">
                                <div class="col-sm-3 col-xs-5">
                                    <div class="cart-img">
                                        <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"> <img src="{{getFtpImage($image)}}" alt="{{$products->products_name}}">
                                       </a>
                                    </div>
                                    <div class="qty d-lg-none d-md-none d-sm-none" style="display: block;">
                                        @if( $products->semi_stitched)
                                        <a class="btn" >semi stitched</a>
                                        @endif
                                        <br>
                                        <div class="qty-in">
                                            <span class="fa fa-minus qtyminus_{{$products->customers_basket_id}}"></span>
                                            <input  type="text" readonly value="{{$products->customers_basket_quantity}}" class="form-control qty" maxlength="{{$products->products_quantity}}"> 
                                            <span class="fa fa-plus qtypluscart_{{$products->customers_basket_id}}"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9 col-xs-7">
                                    <div class="cart-detail">
                                        <p><b>{{$products->products_name}}</b></p>
                                        <div class="qty d-none-420">
                                            @if( $products->semi_stitched)
                                            <a class="btn" >semi stitched</a>
                                            @endif
                                            <div class="qty-in">
                                                <span class="fa fa-minus qtyminus_{{$products->customers_basket_id}}"></span>
                                                <input   type="text" readonly value="{{$products->customers_basket_quantity}}" class="form-control qty" maxlength="{{$products->products_quantity}}"> 
                                                <span class="fa fa-plus qtypluscart_{{$products->customers_basket_id}}"></span>
                                            </div>
                                        </div>
                                        <p>RIF :  {{$products->model}}</p>
                                        <h3>{{$web_setting[19]->value}}{{$products->final_price * $products->customers_basket_quantity}}</h3>
                                        <a class="remove" href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach  
                        </div>
                        <div class="spacer-40"></div>
                        <div class="action-btn">
                            <a href="{{ URL::to('/shop')}}" class="btn btn-primary btn-dark">Back To Shopping</a>
                            <a  class="btn btn-primary" id="update_cart">Update Cart</a>
                        </div>
                        <div class="spacer-40"></div>
                    </form>
                </div>
            </div>
            <div class="col-md-4 mobile-white-bg">
                <div class="spacer-40 d-xs-none-cu"></div>
                <div class="order-sumerry">
                    <h4>@lang('website.Order Summary')</h4>
                    <form id="apply_coupon" class="form-validate">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search" name="coupon_code" id="coupon_code" >

                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit">@lang('website.Apply')</button> 
                            </div>
                        </div>
                        <div id="coupon_error" class="help-block" style="display: none"></div>
                        <div id="coupon_require_error" class="help-block" style="display: none">@lang('website.Please enter a valid coupon code')</div>
                    </form>
                    <div class="clear"></div>
                    <dl>
                        <dt>@lang('website.SubTotal')</dt>
                        <dd id="subtotal">{{$web_setting[19]->value}}{{$price+0}}</dd>
                        <dt>@lang('website.Discount')</dt>
                        <dd  id="discount">{{$web_setting[19]->value}}{{number_format((float)session('coupon_discount'), 2, '.', '')+0}}</dd>
                        <!-- <dt>Tax</dt>
                        <dd>$113.56</dd>-->
                        <dt>Delivery Charges </dt>
                        <dd>$0</dd> 
                        <dt>Grand Total</dt>
                        <dd id="total_price">{{$web_setting[19]->value}}{{$price+0-number_format((float)session('coupon_discount'), 2, '.', '')}}</dd>
                    </dl>
                    @if(count(session('coupon')) > 0 and !empty(session('coupon')))
                    <div class="form-group"> 
                        <label>@lang('website.Coupon Applied')</label>         
                        @foreach(session('coupon') as $coupons_show)  
                            <div class="alert alert-success">
                                <a href="{{ URL::to('/removeCoupon/'.$coupons_show->coupans_id)}}" class="close"><span aria-hidden="true">&times;</span></a>
                                {{$coupons_show->code}}
                            </div>
                        @endforeach
                    </div>         
                    @endif  
                    <div class="action-btn">
                        <a href="{{ URL::to('/checkout')}}" class="btn btn-primary btn-dark color-white">@lang('website.proceedToCheckout')</a>
                    </div>
                </div>
            </div>
            @else
            <div class="col-xs-12 col-sm-12 page-empty">
                <span class="fa fa-cart-arrow-down"></span>
                <div class="page-empty-content">
                    <span>@lang('website.cartEmptyText')</span>
                </div>
            </div>
           @endif
        </div>
    </div>
    <div class="clear"></div>
</section>
<script>
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

            }
        },
        error: function (reject,exception) {
            jQuery('#loader').css('display','none');
            notification( reject.responseJSON );
        }
    });
});


    
//cart page
@if( !empty($result['cart']) and count($result['cart']) > 0)
@foreach( $result['cart'] as $products)
    // This button will increment the value
    jQuery('.qtypluscart_{{$products->customers_basket_id}}').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = jQuery(this).attr('field');
        // Get its current value
        var currentVal = parseInt(jQuery(this).prev('.qty').val());
        var maxProductQty = parseInt(jQuery(this).prev('.qty').attr('maxlength'));
 
        if(maxProductQty > currentVal) {
            // If is not undefined
            if (!isNaN(currentVal)) {               
                // Increment
                jQuery(this).prev('.qty').val(currentVal + 1);
                 
                jQuery("#qty_{{$products->customers_basket_id}}").val(currentVal + 1);
            } else {
                // Otherwise put a 0 there
                jQuery(this).prev('.qty').val(0);
                jQuery("#qty_{{$products->customers_basket_id}}").val(0);
            }
        } else {
            notification('Product quantity limit is exceeded than  available limit.');
        }
        
    });

    // This button will decrement the value till 0
    jQuery(".qtyminus_{{$products->customers_basket_id}}").click(function(e) {

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
            jQuery("#qty_{{$products->customers_basket_id}}").val(currentVal - 1);
            
        } else {
            // Otherwise put a 0 there
            jQuery(this).next('.qty').val(1);
            jQuery("#qty_{{$products->customers_basket_id}}").val(1);
        }
        
    });

@endforeach
@endif
</script>
@endsection