<?php $qunatity=0; ?>  
@if(count($result['commonContent']['cart'])>0)              
@foreach($result['commonContent']['cart'] as $cart_data)                
    <?php $qunatity += $cart_data->customers_basket_quantity; ?>                    
@endforeach
@endif
<!--  href="{{ URL::to('/viewcart')}}"-->
<a   id="cart-icon-btn">
    <i class="fa fa-shopping-cart"></i>
    <spam>{{ count(@$result['commonContent']['cart']) }}</spam>  
    <span>Cart</span>
</a>
<div class="shopping-cart" style="display: none;">
<?php
    $total_amount=0;
    $qunatity=0;
?>
    @if(count($result['commonContent']['cart'])>0)
    <div class="shopping-cart-header">
       Recently Added Item(s)  
       <div class="pull-right btn-close-top" id="cart-close"><i class="fa fa-times" aria-hidden="true"></i></div>
    </div> <!--end shopping-cart-header -->
    <ul class="shopping-cart-items list-unstyled" id="cart-items-popup-content">
    @foreach($result['commonContent']['cart'] as $cart_data)
        <li class="clearfix item">
            <div class="cartImgBox">
            <?php 
                $total_amount += $cart_data->final_price*$cart_data->customers_basket_quantity;
                $qunatity     += $cart_data->customers_basket_quantity;

                $image=$cart_data->image;
                if( isset($cart_data->customers_basket_attributes->image) ) {   
                   $image = $cart_data->customers_basket_attributes->image;
                } 
            ?>
            <img src="{{getFtpImage($image)}}" alt="{{$cart_data->products_name}}" class="img-responsive center-block">

            <div class="clearfix"></div>
            </div>
            <span class="item-name">{{$cart_data->products_name}}<br><span class="item-price">{{$cart_data->customers_basket_quantity}}x{{$web_setting[19]->value}}{{$cart_data->final_price*$cart_data->customers_basket_quantity}}</span></span>
            <div class="clearfix"></div>
            <div class="qty">
                @if( $cart_data->semi_stitched)
                <a class="btn" >semi stitched</a>
                @endif
               <!--  <div class="qty-in">
                    <span class="fa fa-minus"></span>
                    <input type="text" placeholder="1">
                    <span class="fa fa-plus"></span>
                </div> -->
             <div class=" remove-cart"  href="javascript:void(0)" onClick="delete_cart_product({{$cart_data->customers_basket_id}})">Remove</div>
            </div>
        </li>
    @endforeach
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <div class="gray-bg">
                <div class="text-left cart-price">
                    <span  class="fsize cart-total-amt">{{$web_setting[19]->value}}{{ $total_amount }}</span>
                    <a href="{{URL::to('checkout')}}" class="btn pull-right">
                     <span  >Checkout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>                                                                                @endif                                            
</div>

<!-- <a class="cart-icon" onclick="getmodal();">
<img src="{{asset('images/cart_icon.png')}}" alt="photo icon">
<span class="cart-number">{{ $qunatity }}</span></a>
<div class="cart-modall">
 
@if(count($result['commonContent']['cart'])>0)
     <?php
        $total_amount=0;
        $qunatity=0;
    ?>
    @foreach($result['commonContent']['cart'] as $cart_data)

    <?php 
    $total_amount += $cart_data->final_price*$cart_data->customers_basket_quantity;
    $qunatity     += $cart_data->customers_basket_quantity; ?>
        <div class="cart-box">
            <div class="cart-img">              
                <?php
                    $image=$cart_data->image;
                    if( isset($cart_data->customers_basket_attributes->image) )
                    {   
                       $image = $cart_data->customers_basket_attributes->image;
                    }
                ?>
                <img src="{{getFtpImage($image)}}" width="auto" height="70" alt="{{$cart_data->products_name}}">

                <a href="javascript:void(0)" onClick="delete_cart_product({{$cart_data->customers_basket_id}})" class="close-icon">
                <img src="{{asset('images/close_icon.png')}}" alt="cart icon"></a>
                
            </div>
            <div class="cart-content">
                <span class="detail">{{$cart_data->products_name}}</span>
                <span class="qty">@lang('website.Qty') : {{$cart_data->customers_basket_quantity}}</span>
                <span class="price" href="#">{{$web_setting[19]->value}}{{$cart_data->final_price*$cart_data->customers_basket_quantity}}</span>
            </div>
        </div>
   @endforeach    
        

        <div class="total">
            <span class="item">@lang('website.items')</span>
            <span class="total-qty">{{ $qunatity }}</span>
            <span class="sub-total">@lang('website.SubTotal')</span>
            <span class="sub-total-price">{{$web_setting[19]->value}}{{ $total_amount }}</span>
        </div>

        <div class="checkout">
            <a href="{{ URL::to('/viewcart')}}" class="view-cart">@lang('website.View Cart')</a>
            <a href="{{ URL::to('/checkout')}}" class="checkout-btn">@lang('website.Checkout')</a>
        </div>
        @else
        <div class="cart-box text-left">
        @lang('website.You have no items in your shopping cart')
        </div>
@endif
    
</div>
                    
    -->