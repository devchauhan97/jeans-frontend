@extends('layouts')
 
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> <!-- Popper plugin for Bootstrap -->
<!-- jQuery Twitter Feeds plugin -->
<!-- <script src="{!! asset('vendor/owl-carousel/js/owl.carousel.min.js') !!}"></script> -->
<!-- jQuery Owl Carousel plugin -->
 
<?php
    $breadcrumb_name ='New Arrivals';

    if(app('request')->input('category') == 'all')
        $breadcrumb_name =  'All Styles';
    else if($result['sub_category_name'])
        $breadcrumb_name=$result['sub_category_name'];
    else if($result['category_name'])
        $breadcrumb_name=$result['category_name'];
    else {
        //$breadcrumb_name = 'Fillter By : ';
       /* $category_selected=[];
        foreach($result['commonContent']['categories'] as $categories_data) {

            if(!empty( $result['filter_categories'] ) and in_array( $categories_data->categories_description->categories_id,$result['filter_categories'] ))  
             $category_selected[]= $categories_data->categories_description->categories_name;

        }

        $breadcrumb_name .= implode(',',$category_selected);
 
        $option_seleted=[]; 
        foreach($result['filters']['attr_data'] as $key=>$attr_data)
        foreach($attr_data['values'] as $key=>$values)
        if(!empty($result['filter_attribute']['option_values']) and in_array($values['value_id'],$result['filter_attribute']['option_values'])) 
            $option_seleted[]=$values['value'];
        if( count($category_selected) && count($option_seleted))
           $breadcrumb_name .=',' ;

        $breadcrumb_name .= implode(',',$option_seleted);*/
    }

?>
<!-- Site Page Header -->
<section class="page-header" style="height: 123px;">
</section>
<!-- Site Content -->
<section class="content  category-bg category" id="site-content">
    <div class="container">
        <form method="get" enctype="multipart/form-data" id="load_products_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            @if(!empty(app('request')->input('search')))
            <input type="hidden"  name="search" value="{{ app('request')->input('search') }}">
            @endif
            @if(!empty(app('request')->input('category')))
            <input type="hidden"  name="category" value="@if(app('request')->input('category')!='all'){{ app('request')->input('category') }} @endif">
            @endif
            <input type="hidden"  name="load_products" value="1">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <form enctype="multipart/form-data" name="filters" method="get">
                        <input type="hidden" name="min_price" id="min_price"  value="{{app('request')->input('min_price')}}">
                        <input type="hidden" name="max_price" id="max_price"  value="{{app('request')->input('max_price')}}">
                        @if(app('request')->input('filters_applied')==1)
                        <input type="hidden" name="filters_applied" id="filters_applied" value="1">
                        <input type="hidden" name="options" id="options" value="<?php echo implode($result['filter_attribute']['options'],',')?>">
                        <input type="hidden" name="options_value" id="options_value" value="<?php echo implode($result['filter_attribute']['option_values'], ',')?>">
                        @else
                        <input type="hidden" name="filters_applied" id="filters_applied" value="0">
                        @endif
                        <div class="aside-left">
                            <h4>Shop By</h4>
                            <div class="clear"></div>

                            <div class="custom-checkbox p-0">
                            </div>

                            <div class="clear"></div>
                            <h4>@lang('website.Price')</h4>
                            <div class="clear"></div>
                            <div class="range-slider">
                                <p>
                                    <div id="slider-values">
                                        <div class="slider-value-0">{{$web_setting[19]->value}}<input type="text" readonly id="min_price_show"></div>
                                        <div class="slider-value-1">{{$web_setting[19]->value}}<input type="text" readonly id="max_price_show"></div>
                                    </div>
                                    <div class="clear"></div>
                                    <input type="hidden" class="maximum_price" value="{{$result['filters']['maxPrice']}}">
                                </p>
                                <div id="slider-range"></div>
                            </div>

                            <div class="clear"></div>
                            <h4>Occasions</h4>
                            <div class="clear"></div>
                            <div class="custom-checkbox occasions_container">
                                <label class="checkbox">All
                                    <input type="checkbox"  class="all_occasions_filters_box" name="all_occasions" value="all_occasions" <?php 
                                        if(!empty( app('request')->input('all_occasions') )) print 'checked'; 
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="checkbox">Wedding
                                    <input type="checkbox"  class="filters_box" name="product_tags[]" value="Wedding" <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Wedding',$result['filter_product_tags'] )) print 'checked'; 
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="checkbox">Party
                                    <input type="checkbox"  class="filters_box" name="product_tags[]" value="Party" <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Party',$result['filter_product_tags'] )) print 'checked'; 
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="checkbox">Festive
                                    <input type="checkbox"  class="filters_box" name="product_tags[]"  value="Festive" <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Festive',$result['filter_product_tags'] )) print 'checked'; 
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="checkbox">Casual
                                    <input type="checkbox"  class="filters_box" name="product_tags[]"  value="Casual" <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Casual',$result['filter_product_tags'] )) print 'checked'; 
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                                
                                <!-- <div class="custom-checkbox hidden">
                                    <label class="checkbox"><h4 class="mt-0">Fabric </h4>
                                        <input type="checkbox" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="custom-checkbox hidden">
                                    <label class="checkbox"><h4 class="mt-0">Type</h4>
                                        <input type="checkbox" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="custom-checkbox hidden">
                                    <label class="checkbox"><h4 class="mt-0">Size</h4>
                                        <input type="checkbox" >
                                        <span class="checkmark"></span>
                                    </label>
                                </div> -->
                                 
                            </div>
                            <div class="spacer-40"></div>
                            <div class="custom-checkbox">
                                <label class="checkbox"><h4 class="mt-0">Designers Collection</h4>
                                    <input type="checkbox"  class="filters_box" name="product_tags[]" value="Designers"  <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Designers',$result['filter_product_tags'] )) print 'checked'; 
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="spacer-40"></div>
                            <div class="custom-checkbox">
                                <label class="checkbox"><h4 class="mt-0">On Sales</h4>
                                    <input type="checkbox"  class="filters_box" name="filter_type" value="special" 
                                    <?php 
                                    if(app('request')->input('filter_type') == 'special' ) print 'checked'; 
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <!-- <div class="clear"></div>
                            <h4>Category</h4>
                            <div class="clear"></div>
                            <div class="custom-checkbox">
                            @foreach($result['commonContent']['categories'] as $categories_data)
                                <label class="checkbox">{{$categories_data->categories_description->categories_name}}
                                    <input type="checkbox" class="filters_box" name="cat[]"  value="{{$categories_data->categories_description->categories_id}}" 
                                    <?php 
                                        // if(!empty( $result['filter_categories'] ) and in_array( $categories_data->categories_description->categories_id,$result['filter_categories'] )) print 'checked';
                                        // else if(in_array( $categories_data->categories_description->categories_id,[$result['selected_category']] )) print 'checked';
                                    ?>
                                    >
                                    <span class="checkmark"></span>
                                </label>
                            @endforeach
                            </div> -->

                            @if(count($result['filters']['attr_data'])>0)
                            <div class="clear"></div>
                            @foreach($result['filters']['attr_data'] as $key=>$attr_data)
                            <h4>{{$attr_data['option']['name']}}</h4>
                            <div class="clear"></div>
                                <div class="custom-checkbox">
                                @foreach($attr_data['values'] as $key=>$values)
                                @if($key<=4)
                                <label class="checkbox">{{$values['value']}}
                                    <input type="checkbox" class="filters_box" name="{{$attr_data['option']['name']}}[]" type="checkbox" value="{{$values['value']}}" <?php 
          if(!empty($result['filter_attribute']['option_values']) and in_array($values['value_id'],$result['filter_attribute']['option_values'])) print 'checked';
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                                @endif
                                @endforeach
                            </div>
                            @endforeach
                            @endif

                             
                            <!-- <h4>Occasion </h4>
                            <div class="clear"></div>
                            <div class="custom-checkbox">
                            @foreach($result['occasion_list'] as $sub_categories_data)     
                                <label class="checkbox">{{$sub_categories_data->categories_name}}
                                    <input type="checkbox" class="filters_box" name="occasion_category[]"  value="{{$sub_categories_data->categories_id}}" 
                                    <?php 
                                     // if(!empty( $result['filter_occasion_category'] ) and in_array( $sub_categories_data->categories_id,$result['filter_occasion_category'] )) print 'checked';
                                        ?>
                                        >
                                    <span class="checkmark"></span>
                                </label>
                             @endforeach
                            </div> -->
                            
                            <div class="spacer-40"></div>
                            <div class="button">
                            <?php
                                $url = '';
                                if(isset($_REQUEST['category_id'])){
                                    $url = "?categories_id=".$_REQUEST['category_id'];
                                }
                                if(isset($_REQUEST['search'])){
                                    $url.= "&search=".$_REQUEST['search'];
                                }
                            ?>
                            <a href="{{ URL::to('/shop/'.$url)}}"  class="btn btn-primary btn-dark" id="apply_options"> @lang('website.Reset') </a>
                            @if(app('request')->input('filters_applied')==1)
                            <button type="button" class="btn btn-primary" onclick="applyFillterForm('load_products_form')"> @lang('website.Apply')</button>
                            @else 
                            <button type="button" class="btn btn-primary" onclick="applyFillterForm('load_products_form')" > @lang('website.Apply')</button>
                            @endif
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-lg-9 col-md-8">
                    <div class="aside-right ptb-40 p-0">
                        <div class="row d-xs-none-cu">
                            <div class="col-md-12">
                                <ul class="breadcrumb">
                                    <li><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                                    <li>Category</li>
                                </ul>
                            </div>
                        </div>
                        @if($result['products']['total_record']>0)
                        <div class="row d-xs-none-cu">
                            <div class="col-md-12">
                                <h3 class="float-left"> {{$breadcrumb_name}}</h3>
                                <div class="short">
                                    <div class="form-group">
                                        <label for="sel1">@lang('website.Sort'):</label>

                                        <select class="form-control sortby" name="type" >
                                            <!-- <option value="atoz" @if(app('request')->input('type')=='atoz') selected @endif>@lang('website.A - Z')</option>
                                            <option value="ztoa" @if(app('request')->input('type')=='ztoa') selected @endif>@lang('website.Z - A')</option> -->
                                            <option value="lowtohigh" @if(app('request')->input('type')=='lowtohigh') selected @endif>@lang('Low To High')</option>
                                            <option value="hightolow" @if(app('request')->input('type')=='hightolow') selected @endif>@lang('High To Low')</option>
                                            <option value="desc" @if(app('request')->input('type')=='desc') selected @endif  @if(app('request')->input('type') == '') selected @endif  >@lang('website.New Arrivals') </option>
                                            <option value="topseller" @if(app('request')->input('type')=='topseller') selected @endif>@lang('website.Best Sellers')</option>
                                            <!-- <option value="special" @if(app('request')->input('type')=='special') selected @endif>@lang('website.Special Products')</option>
                                            <option value="mostliked" @if(app('request')->input('type')=='mostliked') selected @endif>@lang('website.Most Liked')</option> -->
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="spacer-30 d-md-none"></div>
                        <div class="row" id="listing-products">
                            @if($result['products']['success']==1)
                            @foreach($result['products']['product_data'] as $key=>$products)

                            <div class="col-lg-4 col-md-6 col-xs-6">
                                <div class="product-box box-border bottom-box-shadow">
                                   <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}"> <img src="{{getFtpImage($products->products_image)}}" alt="{{$products->products_name}}">
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
                                                <li><a products_id="{{$products->products_id}}" class="is_liked"><i  class="fa @if( $products->liked_customers_id ) fa-heart @else fa-heart-o @endif" aria-hidden="true"></i></a></li>
                                                <li><a ><i class="fa fa-share-alt" aria-hidden="true"></i></a></li>
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
                            @endif
                             
                        </div>
                        <div class="toolbar mt-3">
                            <div class="form-inline">
                                <div class="form-group  justify-content-start col-6">
                                    <input id="record_limit" type="hidden" value="{{$result['limit']}}"> 
                                    <input id="total_record" type="hidden" value="{{$result['products']['total_record']}}"> 
                                    <label for="staticEmail" class="col-form-label"> @lang('website.Showing')<span class="showing_record">{{$result['limit']}} </span> &nbsp; @lang('website.of')  &nbsp;<span class="showing_total_record">{{$result['products']['total_record']}}</span> &nbsp;@lang('website.results')</label>                                            
                                </div>
                                <div class="form-group justify-content-end col-6">
                                    <input type="hidden" value="1" name="page_number" id="page_number">
                                    <?php
                                        if(!empty(app('request')->input('limit'))){
                                            $record = app('request')->input('limit');
                                        }else{
                                            $record = '15';
                                        }
                                    ?>
                                    <button class="btn btn-dark" type="button" id="load_products" 
                                    @if(count($result['products']['product_data']) < $record ) 
                                        style="display:none"
                                    @endif 
                                    >@lang('website.Load More')</button>        
                                </div>
                            </div>
                        </div>
                        @elseif(empty(app('request')->input('search')))
                            <p>@lang('website.Record not found')</p>
                        @endif  
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<div class="mobile-filter d-md-none">
    <ul class="filter-tab">
        <li>
            <a   data-toggle="modal" data-target="#short"><i class="fa fa-random" aria-hidden="true" ></i> Sort</a>
        </li>
        <li>
        <a   data-toggle="modal" data-target="#filter"></i> Filter</a></li>
    </ul>
    <div class="mobile-short">

    </div>
</div>
<div class="modal" id="short">
    <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Short By:-</h4>
              <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <ul>
                  <li><a onclick="mobileSortby('lowtohigh')">Low to High</a></li>
                  <li><a  onclick="mobileSortby('hightolow')">High to Low</a></li>
                  <li><a onclick="mobileSortby('desc')">New Arrivals</a></li>
                  <li><a onclick="mobileSortby('topseller')">Best Sellers</a></li>
              </ul>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel All</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="filter">
    <div class="modal-dialog">
        <form enctype="multipart/form-data" name="filters" method="get" id="load_products_form_mobile">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            @if(!empty(app('request')->input('search')))
            <input type="hidden"  name="search" value="{{ app('request')->input('search') }}">
            @endif
            @if(!empty(app('request')->input('category')))
            <input type="hidden"  name="category" value="@if(app('request')->input('category')!='all'){{ app('request')->input('category') }} @endif">
            @endif
            <input type="hidden"  name="load_products" value="1">

            <input type="hidden" name="min_price" id="min_price_mobile"  value="{{app('request')->input('min_price')}}">
            <input type="hidden" name="max_price" id="max_price_mobile"  value="{{app('request')->input('max_price')}}">
            @if(app('request')->input('filters_applied')==1)
            <input type="hidden" name="filters_applied" id="filters_applied" value="1">
            <input type="hidden" name="options" id="options" value="<?php echo implode($result['filter_attribute']['options'],',')?>">
            <input type="hidden" name="options_value" id="options_value" value="<?php echo implode($result['filter_attribute']['option_values'], ',')?>">
            @else
            <input type="hidden" name="filters_applied" id="filters_applied" value="0">
            @endif 

            <input  type="hidden" name="type" id="mobileSortby" >

            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Filters:-</h4>
                  <a  class="close btn btn-danger" data-dismiss="modal">Clear All</a>
                </div>
                <!-- Modal body -->
               
                <div class="modal-body">
                    <div class="tab">
                        <a class="tablinks active" onclick="openCity(event, 'Designers')">Designers</a>
                        <a class="tablinks" onclick="openCity(event, 'Price')">Price</a>
                        <a class="tablinks" onclick="openCity(event, 'Occasions')">Occasions</a>
                        <a class="tablinks" onclick="openCity(event, 'Sales')">Sales</a>

                        @if(count($result['filters']['attr_data'])>0)
                        @foreach($result['filters']['attr_data'] as $key=>$attr_data)
                        <a class="tablinks" onclick="openCity(event,'{{$attr_data['option']['name']}}')">{{$attr_data['option']['name']}}</a>
                        @endforeach
                        @endif
                    </div>
                    <div class="content">
                            
                        <div id="Occasions" class="tabcontent occasions_container">
                            <div class="custom-checkbox">
                                <label class="checkbox">All
                                    <input type="checkbox"  class="all_occasions_filters_box" name="all_occasions" value="all_occasions" <?php 
                                        if(!empty( app('request')->input('all_occasions') )) print 'checked'; 
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="checkbox">Wedding
                                    <input type="checkbox"  class="filters_box" name="product_tags[]" value="Wedding" <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Wedding',$result['filter_product_tags'] )) print 'checked'; ?>>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="checkbox">Party
                                    <input type="checkbox"  class="filters_box" name="product_tags[]" value="Party" <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Party',$result['filter_product_tags'] )) print 'checked'; ?>>
                                    <span class="checkmark"></span>
                                </label>
                                <label class="checkbox">Festive
                                    <input type="checkbox"  class="filters_box" name="product_tags[]" value="Festive" <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Festive',$result['filter_product_tags'] )) print 'checked'; ?>>
                                    <span class="checkmark"></span>
                                </label>
                                 <label class="checkbox">Casual
                                    <input type="checkbox"  class="filters_box" name="product_tags[]" value="Casual" <?php 
                                        if(!empty( $result['filter_product_tags'] ) and in_array( 'Casual',$result['filter_product_tags'] )) print 'checked'; ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                            
                        <div id="Price" class="tabcontent">
                            <div class="spacer-30"></div>
                            <div class="range-slider">
                                <p>
                                    <div id="slider-values">
                                        <div class="slider-value-0">{{$web_setting[19]->value}}<input type="text" readonly id="min_price_show_mobile"></div>
                                        <div class="slider-value-1">{{$web_setting[19]->value}}<input type="text" readonly id="max_price_show_mobile"></div>
                                    </div>
                                    <div class="clear"></div>
                                    
                                </p>
                                <div id="slider-range-mobile"></div>
                            </div>
                        </div>

                        <div id="Designers" class="tabcontent">
                            <!-- <h3>Colors</h3> -->
                            <div class="custom-checkbox">
                                <label class="checkbox">Designers
                                    <input type="checkbox" class="filters_box"  name="product_tags[]" value="Designers"  <?php 
                                            if(!empty( $result['filter_product_tags'] ) and in_array( 'Designers',$result['filter_product_tags'] )) print 'checked'; 
                                        ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div id="Sales" class="tabcontent">
                            <div class="custom-checkbox">
                                <label class="checkbox">On Sales
                                    <input type="checkbox"  class="filters_box" name="filter_type" value="special" 
                                        <?php 
                                        if(app('request')->input('filter_type') == 'special' ) print 'checked'; 
                                        ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>

                        @if(count($result['filters']['attr_data'])>0)
                        @foreach($result['filters']['attr_data'] as $key=>$attr_data)
                        <div id="{{$attr_data['option']['name']}}" class="tabcontent">
                            <div class="custom-checkbox">
                        @foreach($attr_data['values'] as $key=>$values)
                        @if($key<=4)
                                <label class="checkbox">{{$values['value']}}
                                    <input type="checkbox" class="filters_box" name="{{$attr_data['option']['name']}}[]" type="checkbox" value="{{$values['value']}}" <?php 
          if(!empty($result['filter_attribute']['option_values']) and in_array($values['value_id'],$result['filter_attribute']['option_values'])) print 'checked';
                                    ?>>
                                    <span class="checkmark"></span>
                                </label>
                                @endif
                        @endforeach
                            </div>
                        </div>
                        @endforeach
                        @endif

                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer"> 
                    <button type="button" class="btn float-left"   data-dismiss="" style="background: transparent;" onclick="applyFillterForm('load_products_form_mobile')">Apply All</button>
                    <a  class="btn btn-danger" data-dismiss="modal">Cancel All</a>
                </div>
            
            </div>
        </form> 
    </div>
</div>
<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
// Get the element with id="defaultOpen" and click on it
// document.getElementById("defaultOpen").click();

/*$(document).on('click', '#apply_options_btn', function(e){ 

    if (jQuery('input:checkbox.filters_box:checked').length > 0) {
        //jQuery('[name=category]').val('');
        jQuery('#filters_applied').val(1);
        jQuery('#apply_options_btn').removeAttr('disabled');
    } else {
        jQuery('#filters_applied').val(0);
        jQuery('#apply_options_btn').attr('disabled',true);
    }   
    jQuery('#load_products_form').submit();
    
})*/


function applyFillterForm(formId) {
    
    if (jQuery('input:checkbox.filters_box:checked').length > 0) {
        //jQuery('[name=category]').val('');
        jQuery('[name=filters_applied]').val(1);
        //jQuery('#apply_options_btn').removeAttr('disabled');
    } else {
        jQuery('[name=filters_applied]').val(0);
       // jQuery('#apply_options_btn').attr('disabled',true);
    }  

    jQuery('#'+formId).submit();
}

jQuery(document).ready(function(){
    jQuery('.all_occasions_filters_box').change(function(){

        jQuery('.occasions_container input:checkbox').prop('checked', jQuery(this).prop("checked"));
        
    })
})

// jQuery(document).on('change', 'mobile_sortby', function(e){   
//     jQuery('#loader').css('display','flex');
//     jQuery("#load_products_form_mobile").submit();
// });
function mobileSortby(sortBy) {

    jQuery('#mobileSortby').val(sortBy);
    jQuery('#load_products_form_mobile').submit();
}
</script>
@endsection 