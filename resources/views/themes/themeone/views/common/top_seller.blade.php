<section class="featured-product padding-50 light-grey">
    <div class="wrapper">
        <h2 class="text-center">Top seller</h2>
        <div class="row">
        @foreach($result['top_seller']['product_data'] as $key=>$products) 
        @if($key<=3)
            <div class="col-md-3 col-sm-6 col-3">
                
                <div class="bg-grey text-center">
                <img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}" width="300"> 
                <div class="wish-middle">
                    <a href="{{ URL::to('/wishlist')}}"><span class="wishlist-icon" id="wishlist-count">
                        <img src="{{asset('public/images/wishlist_icon.png')}}" alt="wishlist"></span></a>
                    <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><span class="wishlist-icon"><img src="{{asset('public/images/eye_icon.png')}}" alt="wishlist"></span></a>
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
    


<!-- <section class="latest-products-section padding-50">
    <div class="wrapper">
        <h2 class="text-center">latest products</h2>
        <div class="row">
             @foreach($result['featured']['product_data'] as $key=>$products)
              @if($key<=2)
                <div class="col-md-4 col-sm-4 col-4">
                    <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><img class="left-img" src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}} Jean1"></a>
                </div>
             @endif
             @endforeach 
             <div class="col-md-4 col-sm-4 col-4">
                <a href="#"><img class="left-img" src="{{asset('public/images/trendy_jean5.jpg')}}" alt="Trendy Jean1"></a>
            </div>
            <div class="col-md-4 col-sm-4 right-img col-4">
                <a href="#"><img src="{{asset('public/images/trendy_jean6.jpg')}}" alt="Trendy Jean3"></a>
            </div> 
        </div>
    </div>
</section> -->