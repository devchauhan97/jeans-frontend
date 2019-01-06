              
<?php $qunatity=0; ?>                
                @foreach($result['commonContent']['cart'] as $cart_data)                
                    <?php $qunatity += $cart_data->customers_basket_quantity; ?>                    
                @endforeach
               <a class="cart-icon" onclick="getmodal();"  href="#"><img src="{{asset('').'images/cart_icon.png'}}" alt="photo icon">
                    <span class="cart-number">{{ $qunatity }}</span></a>

           <div class="cart-modall">
           <!-- cart modal -->
    
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
                <img src="{{getFtpImage($cart_data->image)}}" width="auto" height="70" alt="{{$cart_data->products_name}}">
                <a href="javascript:void(0)" onClick="delete_cart_product({{$cart_data->customers_basket_id}})" class="close-icon">
                <img src="{{asset('').'images/close_icon.png'}}" alt="cart icon"></a>
                
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
                    
   