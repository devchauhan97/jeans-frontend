@extends('layouts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> <!-- Popper plugin for Bootstrap -->
<style type="text/css">
.has-error  {
  border-color: #a94442;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
  }
</style>
@section('content')
<section class="page-header" style="height: 120px;">
</section>
  <!-- Site Content -->
<section class="content main-container category-bg" id="site-content">
  <div class="container">
    <div class="row">
      <div class="col-md-8 white-bg border-right-1">
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/')}}">@lang('website.Home')</a></li>
          <li>@lang('website.Checkout')</li>
        </ul>
        <div class="checkout-form">
          <div class="checkout-title-bar">
            <div class="col-md-offset-3 col-md-6">
              <div class="track-points">
                <div class="big-line gray-bg-100">
                  <div class="first-line gray-bg-100 orange-bg">
                    <span class="track-points-span gray-bg-100 orange-bg white-color">1</span>
                    <p class="track-points-p">@lang('website.Cart')</p>
                  </div>
                  <div class="second-line gray-bg-100 orange-bg">
                    <span class="track-points-span gray-bg-100 orange-bg white-color">2</span>
                    <p class="track-points-p">@lang('website.Address')</p>
                  </div>
                  <div class="three-line gray-bg-100">
                    <span class="track-points-span gray-bg-100 black-color">3</span>
                    <p class="track-points-p">@lang('website.Payment')</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="ptb-30 border-top border-bottom">
            <div class="form">
              <h4>@lang('website.Select Exiting Address')</h4>
              @foreach($result['address'] as $val)
              <div class="row">
                <a onclick="choseShippingAddress('{{$val->address_id}}')">{{$val->firstname
            .' '.$val->lastname.' ,'.$val->street.' ,'.$val->city.' ,'.$val->zone_name.' ,'.$val->postcode
               }}</a>
               <i class="fa fa-trash deleteShippingMyAddress" aria-hidden="true" address_id="{{$val->address_id}}"></i>
              </div>
              @endforeach
            </div>
          </div>
          @if( count($errors) > 0)
          @foreach($errors->all() as $error)
          <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">@lang('website.Error'):</span>
              {{ $error }}
          </div>
          @endforeach
          @endif
          <form name="signup" enctype="multipart/form-data" class="form-validate" action="{{ URL::to('/checkout/shipping/address')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="ptb-30">
              <div class="form">
                <h4>Add New Address</h4>
                <div class="row">
                  <div class="col-md-6">
                    <div class="label">
                      <span>@lang('website.First Name')*</span>
                      <input type="text" placeholder="First Name*" class="form-control field-validate" id="firstname" name="firstname" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->firstname}}@endif">
                      <spam class="help-block error-content" hidden>@lang('website.Please enter your first name')</spam>  
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="label">
                      <span>@lang('website.Last Name')*</span>
                      <input type="text" placeholder="Last Name" class="form-control field-validate" id="lastname" name="lastname" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->lastname}}@endif">
                      <spam class="help-block error-content" hidden>@lang('website.Please enter your last name')</spam> 
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="label">
                      <span>@lang('website.Address')*</span>
                      <input type="text" placeholder="Address*" class="form-control field-validate" id="street" name="street" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->street}}@endif">
                      <spam class="help-block error-content" hidden>@lang('website.Please enter your address')</spam> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="label">
                      <span>@lang('website.Country')*</span>
                      <select class="form-control field-validate" id="entry_country_id" onChange="getZones();" name="countries_id">
                        <option value="" selected>@lang('website.Select Country')</option>
                        @if(count($result['countries'])>0)
                          @foreach($result['countries'] as $countries)
                              <option value="{{$countries->countries_id}}" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->countries_id == $countries->countries_id) selected @endif @endif >{{$countries->countries_name}}</option>
                          @endforeach
                        @endif
                      </select>
                      <spam class="help-block error-content" hidden>@lang('website.Please select your country')</spam> 
                    </div>
                  </div>
                </div>
                 
                <div class="row">
                  <div class="col-md-6">
                    <div class="label">
                      <span>@lang('website.State')*</span>
                      <select class="form-control field-validate" id="entry_zone_id" name="zone_id">
                          <option value="" selected>@lang('website.Select State')</option>
                           @if(count($result['zones'])>0)
                            @foreach($result['zones'] as $zones)
                                <option value="{{$zones->zone_id}}" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                            @endforeach
                          @endif
                          
                          <option value="Other" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>                      
                      </select>
                      <spam class="help-block error-content" hidden>@lang('website.Please select your state')</spam>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="label">
                      <span>@lang('website.City')</span>
                      <input type="text" placeholder="City*" class="form-control field-validate" id="city" name="city" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->city}}@endif">
                      <spam class="help-block error-content" hidden>@lang('website.Please enter your city')</spam> 
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="label">
                      <span>@lang('website.Zip/Postal Code')*</span>
                      <input type="text" placeholder="Alternate Phone (Optional)*" class="form-control field-validate" id="postcode" name="postcode" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->postcode}}@endif">
                      <spam class="help-block error-content" hidden>@lang('website.Please enter your Zip/Postal Code')</spam> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="label">
                      <span>@lang('website.Phone No') </span>
                      <input type="text" placeholder="Phone No" id="phone_no" name="phone_no" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->phone_no}}@endif">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="label">
                      <button type="submit" class="btn btn-primary btn-dark">@lang('website.Continue')</button>
                      <a class="btn btn-primary btn-secondary" href="{{URL::to('/viewcart')}}">Go Back</a>
                    </div>
                  </div>
                </div>
                <div class="cart-title text-left">
                  <h4>@lang('website.Delivery Instructions')</h4>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="cart-box">
          <h4>@lang('website.Order Summary')</h4>
          <?php 
            $price = 0;
            $not_available_qty = false;
          ?>
          <form method='POST' id="update_cart_form" action='{{ URL::to('/place_order')}}' >
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="cart-product border-0">
              @foreach( $result['cart'] as $products)
              <?php 
                $price+= $products->final_price * $products->customers_basket_quantity;

                if($products->customers_basket_quantity > $products->quantity)
                  $not_available_qty = true;

                $image = $products->image;
                if( isset($products->customers_basket_attributes->image) )
                {
                  $image = $products->customers_basket_attributes->image;
                }
              ?>
              <div class="row">
                <div class="col-sm-3 col-xs-5">
                  <div class="cart-img">
                  <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">
                    <img src="{{getFtpImage($image)}}" alt="{{$products->products_name}}">
                  </a>
                  </div>
                    <div class="qty d-lg-none d-md-none d-sm-none" style="display: block;">
                      @if( $products->semi_stitched )
                      <a class="btn" >semi stitched</a>
                      <br>
                      @endIf
                      <!-- <div class="qty-in">
                        <span class="fa fa-minus"></span>
                        <input type="text" placeholder="1">
                        <span class="fa fa-plus"></span>
                      </div> -->
                    </div>
                </div>
                <div class="col-sm-9 col-xs-7">
                  <div class="cart-detail">
                    <p><b>{{$products->products_name}}</b></p>
                    <div class="qty d-none-420">
                      @if( $products->semi_stitched )
                      <a class="btn" >semi stitched</a>
                      @endIf
                     <!--  <div class="qty-in">
                        <span class="fa fa-minus"></span>
                        <input type="text" placeholder="1">
                        <span class="fa fa-plus"></span>
                      </div> -->
                    </div>

                    <p>RIF : {{$products->model}}</p>
                    <h3>{{$web_setting[19]->value}}{{$products->final_price+0}}</h3>
                    <a class="remove" href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</a>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </form>
          <div class="spacer-40"></div>
        </div>
      </div>
      <?php     
        if( !empty(session('shipping_detail')) and count(session('shipping_detail'))>0 ) {
            $shipping_price = session('shipping_detail')->shipping_price;
            $shipping_name = session('shipping_detail')->mehtod_name;
        } else {
            $shipping_price = 0;
            $shipping_name = '';
        }       
        $tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
        $coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');        
        $total_price = ($price+$tax_rate+$shipping_price)-$coupon_discount;         
      ?>
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
            <dd>{{$web_setting[19]->value}}{{$price+0}}</dd>
            <dt>@lang('website.Tax')</dt>
            <dd>{{$web_setting[19]->value}}{{$tax_rate}}</dd>
            <dt>@lang('website.Delivery Charges') </dt>
            <dd>{{$web_setting[19]->value}}{{$shipping_price}}</dd>
            <dt>@lang('website.Discount') </dt>
            <dd id="discount">{{$web_setting[19]->value}}{{number_format((float)session('coupon_discount'), 2, '.', '')+0}}</dd>
            <dt>Grand Total</dt>
            <dd id="total_price">{{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')+0}}</dd>
          </dl>
          @if(count(session('coupon')) > 0 and !empty(session('coupon')))
          <div class="form-group"> 
            <label>@lang('website.Coupon Applied')</label>         
            @foreach(session('coupon') as $coupons_show)  
              <div class="alert alert-success">
                  <a href="{{ URL::to('/removeCoupon/'.$coupons_show->coupans_id)}}" class="close">
                    <span aria-hidden="true">&times;</span></a>
                  {{$coupons_show->code}}
              </div>
            @endforeach
          </div>    
          @endif
          <div class="action-btn">
            <a class="btn btn-primary btn-dark color-white" id="payment_btn" style="display: @if(session('step') != 1) none @endif">@lang('website.Payment')
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</section>
<script type="text/javascript">

  jQuery(document).on('click', '.deleteShippingMyAddress', function(e){

      jQuery('#loader').css('display','flex');
      var address_id = jQuery(this).attr('address_id');
      jQuery(this).parent().remove();
      jQuery.ajax({
        url: '{{ URL::to("/delete/address")}}',
        type: "POST",
        data: '&address_id='+address_id+'&_token='+jQuery('meta[name="csrf-token"]').attr('content'),
        success: function (res) {
          jQuery('#loader').css('display','none');
        },
      });
   });
    
    function choseShippingAddress(address_id) {
      
      jQuery('#loader').css('display','flex');
      jQuery.ajax({
        url: '{{ URL::to("/exiting/address")}}/'+address_id,
        type: "get",
        data: '&_token='+jQuery('meta[name="csrf-token"]').attr('content'),
        dataType: "json",
        success: function (res) {
          jQuery('#loader').css('display','none');

          var address =res.address;
          jQuery('[name=firstname]').val(address.firstname);
          jQuery('[name=lastname]').val(address.lastname);
          jQuery('[name=street]').val(address.street);
          jQuery('[name=city]').val(address.city);
          jQuery('[name=countries_id]').val(address.countries_id);

          var showData = [];
          res.state.forEach(function(currentValue, index, arr) {
             var selected='';
            if(address.zone_id == currentValue['zone_id'])
              selected='selected';
            showData[index] = "<option value='"+currentValue['zone_id']+"' "+selected+">"+currentValue['zone_name']+"</option>"; 
          })
        
          jQuery('[name=zone_id]').html(showData);
          jQuery('[name=zone_id]').trigger('change');
          jQuery('[name=phone_no]').val(address.phone_no);
          jQuery('[name=postcode]').val(address.postcode);
          jQuery('#payment_btn').hide();
        },
      });
    }

  </script>
  @endsection