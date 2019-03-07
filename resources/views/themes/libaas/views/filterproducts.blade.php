@if($result['products']['success']==1)
	@foreach($result['products']['product_data'] as $key=>$products)

    <div class="col-lg-4 col-md-6 col-xs-6">
        <div class="product-box box-border bottom-box-shadow">
            <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}"></a>

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
                        <li><a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
                        <li><a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
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
                            <strike> {{$web_setting[19]->value}}{{$products->products_price+0}}</strike>
                        @else
                            {{$web_setting[19]->value}}{{$products->products_price+0}}
                        @endif
                    </strong>
                </div>
            </div>
        </div>
    </div>
    
    @endforeach
    <input id="filter_total_record" type="hidden" value="{{$result['products']['total_record']}}"> 
    
    @if(count($result['products']['product_data'])> 0 and $result['limit'] > $result['products']['total_record'])
		<style>
			#load_products{
				display: none;
			}
			#loaded_content{
				display: block !important;
			}
			#loaded_content_empty{
				display: none !important;
			}
        </style>
    @endif
    @elseif(count($result['products']['product_data'])==0 or $result['products']['success']==0)
		<style>
            #load_products{
                display: none;
            }
            #loaded_content{
                display: none !important;
            }
            #loaded_content_empty{
                display: block !important;
            }
        </style>
    @endif
