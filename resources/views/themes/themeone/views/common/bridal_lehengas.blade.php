<section class="featured-product padding-50 light-grey">
    <div class="wrapper">
        <h2 class="text-center">bridal lehengas</h2>
        <div class="row">
        @foreach($result['bridal_lehengas'] as $key=>$products) 
        @if($key<=3)
            <div class="col-md-3 col-sm-6 col-3">
                <?php    $current_date = date("Y-m-d", strtotime("now"));
                    $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
                    $date=date_create($string);
                    date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                    $after_date = date_format($date,"Y-m-d"); 

                    if($after_date>=$current_date){
                        print '<span class="new-tag">New</span>';
                    }
                ?>
                <div class="bg-grey text-center">
                <img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}" width="300"> 
                <div class="wish-middle">
                    <a href="{{ URL::to('/wishlist')}}"><span class="wishlist-icon" id="wishlist-count">
                        <img src="{{asset('images/wishlist_icon.png')}}" alt="wishlist"></span>
                    </a>
                    <?php
                        $parm='?';
                        if($products->default_products_attributes){
                            $parm.= $products->default_products_attributes->default_products_option->products_options_name;
                            $parm.= '='.$products->default_products_attributes->default_products_options_values->products_options_values_id;
                        }else{
                            $parm='';
                        }
                    ?>
                    <a href="{{ URL::to('/product-detail/'.$products->products_slug.$parm)}}">
                        <span class="wishlist-icon"><img src="{{asset('images/eye_icon.png')}}" alt="wishlist"></span></a>
                </div>              
                <div class="add-cart">
                     @if(!in_array($products->products_id,$result['cartArray']))
                    <span class="cart" products_id="{{$products->products_id}}">Add to Cart</span>
                    @else
                        <span>Added</span>
                    @endif
                </div>
                </div>
                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="featured-content text-center">
                    <span class="product-title">{{$products->products_name}}</span>
                    <div class="pricing">
                       @if(!empty($products->discount_price))
                       {{$web_setting[19]->value}}{{$products->discount_price+0}}
                        <span class="text-strike">
                        <strike>{{$web_setting[19]->value}}{{$products->products_price+0}}</strike>
                        </span>
                         @else 
                        <span class="pink"> {{$web_setting[19]->value}} {{$products->products_price+0}}</span>
                         @endif
                    </div>
                </a>
            </div>
              @endif
                @endforeach

    </div>
    
</section>
 