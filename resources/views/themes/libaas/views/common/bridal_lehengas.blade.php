<div class="owl-carousel owl-four owl-theme ">
    @foreach($result['bridal_lehengas'] as $key=>$products) 

    <div class="item">

        <div class="product-box box-border bottom-box-shadow">
            <img src="{{getFtpImage($products->products_image)}}" lt="{{$products->products_name}}" >
            <?php    $current_date = date("Y-m-d", strtotime("now"));
                $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
                $date=date_create($string);
                date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));
                $after_date = date_format($date,"Y-m-d"); 

                if($after_date>=$current_date){
                    print '<div class="badge">N<br>E<br>W</div>';
                }
             
                $parm='?';
                if($products->default_products_attributes){
                    $parm.= $products->default_products_attributes->default_products_option->products_options_name;
                    $parm.= '='.$products->default_products_attributes->default_products_options_values->products_options_values_id;
                }else{
                    $parm='';
                }
            ?>
            <div class="product-action">
                <div class="action">
                    <ul>
                        <li><a href="{{ URL::to('/wishlist')}}"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-arrows" aria-hidden="true"></i></a></li>
                        <li><a href="{{ URL::to('/product-detail/'.$products->products_slug.$parm)}}"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
                <div class="clear"></div>
                <div class="action-title">
                    <p>
                        {{$products->products_name}}
                    </p>
                    <strong>
                        @if(!empty($products->discount_price))
                       {{$web_setting[19]->value}}{{$products->discount_price+0}}
                        <span class="text-strike">
                        <strike>{{$web_setting[19]->value}}{{$products->products_price+0}}</strike>
                        </span>
                         @else 
                        <span class="pink"> {{$web_setting[19]->value}} {{$products->products_price+0}}</span>
                         @endif
                    </strong>
                </div>
            </div>
        </div>
    </div>
     @endforeach 
</div>