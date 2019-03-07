    <div class="wrapper">
        <h2 class="text-center">Product Spotlight</h2>
         <div class="product-detail-area">
            <div class="row">
                <div class="col-12">
                    <div class="detail-area">
                        <div class="row">
                            <div class="col-4 col-lg-2">
                                <div id=" " class="carousel slide" >
                                     <a href="{{getFtpImage($result['detail']['product_data'][0]->products_image) }}" class = 'cloud-zoom' id='zoom1'
        rel="zoomWidth:'100', zoomHeight:'200', adjustY:0, adjustX:10">
                                        <img class="img-thumbnail" src="{{getFtpImage($result['detail']['product_data'][0]->products_image) }}" alt="img-fluid">
                                    </a>
                                 </div>
                            </div>
            
                            <div class="col-4 col-lg-2">
                                <div class="product-summary">
                                    <div class="like-box">
                                        <span products_id='{{$result['detail']['product_data'][0]->products_id}}' class="fa @if($result['isLiked']==1) fa-heart @else fa-heart-o @endif is_liked">
                                            <span class="badge badge-secondary">{{$result['detail']['product_data'][0]->products_liked}}</span>
                                        </span>                                          
                                    </div>                                    
                                    <h3 class="product-title">{{$result['detail']['product_data'][0]->products_name}}</h3> 
                                    
                                    <div class="product-info">
                                        
                                        @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                                            
                                         <a href="{{ URL::to('/shop?category='.$result['sub_category_slug'])}}" class="category">{{$result['sub_category_name']}}</a>
                                        @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                                         <a href="{{ URL::to('/shop?category='.$result['category_slug'])}}" class="category">{{$result['category_name']}}</a>
                                            
                                        @endif
                                        
                                         @if($result['detail']['product_data'][0]->products_quantity == 0)
                                            <div class="availbility"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.Out of Stock') </div>
                                        @elseif($result['detail']['product_data'][0]->products_quantity <= $result['detail']['product_data'][0]->low_limit )
                                            <div class="availbility"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.Low in Stock') </div>
                                        @else 
                                            <div class="availbility"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; @lang('website.In stock') </div>
                                        @endif
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
                                                    <button class="btn btn-secondary btn-round add-to-Cart" type="button" products_id="{{$result['detail']['product_data'][0]->products_id}}">@lang('website.Add to Cart')</button>
                                                @endif 
                                            </div>   
                                        </div>
                                    </form> 
                                </div>  
                            </div>
                            
                            <div class="col-4 col-lg-2">
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
    </div>
