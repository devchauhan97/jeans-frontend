<div class="owl-carousel owl-five owl-theme">
 @foreach($result['spot_light_product'] as $key=>$products) 
    <?php
        $parm='?';
        if($products->default_products_attributes){
            $parm.= $products->default_products_attributes->default_products_option->products_options_name;
            $parm.= '='.$products->default_products_attributes->default_products_options_values->products_options_values_id;
        }else{
            $parm='';
        }
    ?>
    <div class="item">
        <div class="row">
            <div class="offset-md-1 col-md-10 col-sm-12">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="detail-img box-border bottom-box-shadow">
                            <img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3>{{$products->products_name}}</h3>
                            <p>RIF : {{$products->products_model}}</p>
                        <h3>@if(!empty($products->discount_price))
                       {{$web_setting[19]->value}}{{$products->discount_price+0}}
                        <span class="text-strike">
                        <strike>{{$web_setting[19]->value}}{{$products->products_price+0}}</strike>
                        </span>
                         @else 
                        <span class="pink"> {{$web_setting[19]->value}} {{$products->products_price+0}}</span>
                         @endif</h3>
                        <p>A scintillating silver lehenga with sparkling stone work in delicate florets across the neck and U shaped back. Flattering floral clusters of stone, grace the lehenga and woven diagonals adorn the sleeves.</p>
                        <a class="btn btn-primary btn-secondary" href="{{Url::to('/shop')}}">See All</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
 