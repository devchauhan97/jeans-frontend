@extends('layouts')
@section('customcss')
@if(!empty(session("theme")))
        <link href="{!! asset('css/'.session("theme").'.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @else
        <link href="{!! asset('css/app.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @endif
 <link rel="stylesheet" type="text/css" href="{!! asset('css/bootstrap.min.css') !!}">
 <link rel="stylesheet" type="text/css" href="{!! asset('css/style.min.css') !!}">
<!-- <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}"> -->
<!--  <link href="{!! asset('css/responsive.css') !!} " media="all" rel="stylesheet" type="text/css"/> -->
 <link href="{!! asset('css/rtl.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/font-awesome.css') !!} " media="all" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
<section class="site-content">
  <div class="container">
    <div class="breadcum-area">
        <div class="breadcum-inner">
            <h3>@lang('website.Checkout')</h3>
            <ol class="breadcrumb">                    
                <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">@lang('website.Checkout')</a></li>
                <li class="breadcrumb-item">
                  <a href="javascript:void(0)">
                    @if(session('step')==0)
                          @lang('website.Shipping Address')
                        @elseif(session('step')==1)
                          @lang('website.Billing Address')
                        @elseif(session('step')==2)
                          @lang('website.Shipping Methods')
                        @elseif(session('step')==3)
                          @lang('website.Order Detail')
                        @endif
                  </a>
                </li>
            </ol>
        </div>
    </div>
    <div class="checkout-area">
      <div class="row">
        <div class="col-12 col-lg-8 checkout-left">
          <ul class="nav nav-pills" id="pills-tab" role="tablist">
              <li class="nav-item @if(session('step')==0) active @endif">
                  <a class="nav-link @if(session('step')==0) active @elseif(session('step')>0) active-check @endif" id="shipping-tab" data-toggle="pill" href="#pills-shipping" role="tab" aria-controls="pills-shpping" aria-expanded="true">@lang('website.Shipping Address')</a>
              </li>
              <li class="nav-item @if(session('step')==1) active @endif">
                  <a class="nav-link @if(session('step')==1) active @elseif(session('step')>1) active-check @endif" @if(session('step')>=1) id="billing-tab" data-toggle="pill" href="#pills-billing" role="tab" aria-controls="pills-billing" aria-expanded="true" @endif >@lang('website.Billing Address')</a>
              </li>
              
              <li class="nav-item @if(session('step')==2) active @endif">
                  <a class="nav-link @if(session('step')==2) active @elseif(session('step')>2) active-check @endif"  @if(session('step')>=2)  id="order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-expanded="true"  @endif>@lang('website.Order Detail')</a>
              </li>
          </ul>
           @if( count($errors) > 0)
              @foreach($errors->all() as $error)
                  <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">@lang('website.Error'):</span>
                        {{ $error }}
                  </div>
               @endforeach
            @endif
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade @if(session('step') == 0) show active in @endif" id="pills-shipping" role="tabpanel" aria-labelledby="shipping-tab">
              
              <form name="signup" enctype="multipart/form-data" class="form-validate" action="{{ URL::to('/checkout/shipping/address')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="firstName">@lang('website.First Name')</label>
                      <input type="text" class="form-control field-validate" id="firstname" name="firstname" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->firstname}}@endif">
                       <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>  
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lastName">@lang('website.Last Name')</label>
                      <input type="text" class="form-control field-validate" id="lastname" name="lastname" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->lastname}}@endif">
                      <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span> 
                    </div>
                    <div class="form-group col-md-6">
                      <label for="firstName">@lang('website.Company')</label>
                      <input type="text" class="form-control" id="company" name="company" value="@if(count(session('shipping_address'))>0) {{session('shipping_address')->company}}@endif">
                      <span class="help-block error-content" hidden>@lang('website.Please enter your company name')</span> 
                    </div>
                    <div class="form-group col-md-6">
                      <label for="firstName">@lang('website.Address')</label>
                      <input type="text" class="form-control field-validate" id="street" name="street" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->street}}@endif">
                      <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span> 
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lastName">@lang('website.Country')</label>
                        <select class="form-control field-validate" id="entry_country_id" onChange="getZones();" name="countries_id">
                            <option value="" selected>@lang('website.Select Country')</option>
                            @if(count($result['countries'])>0)
                              @foreach($result['countries'] as $countries)
                                  <option value="{{$countries->countries_id}}" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->countries_id == $countries->countries_id) selected @endif @endif >{{$countries->countries_name}}</option>
                              @endforeach
                            @endif
                        </select>
                      <span class="help-block error-content" hidden>@lang('website.Please select your country')</span> 
                    </div>
                    <div class="form-group col-md-6">
                      <label for="firstName">@lang('website.State')</label>
                      <select class="form-control field-validate" id="entry_zone_id" name="zone_id">
                            <option value="" selected>@lang('website.Select State')</option>
                             @if(count($result['zones'])>0)
                              @foreach($result['zones'] as $zones)
                                  <option value="{{$zones->zone_id}}" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                              @endforeach
                            @endif
                            
                             <option value="Other" @if(count(session('shipping_address'))>0) @if(session('shipping_address')->zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>                      
                      </select>
                      <span class="help-block error-content" hidden>@lang('website.Please select your state')</span> 
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lastName">@lang('website.City')</label>
                      <input type="text" class="form-control field-validate" id="city" name="city" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->city}}@endif">
                      <span class="help-block error-content" hidden>@lang('website.Please enter your city')</span> 
                    </div>
                    <div class="form-group col-md-6">
                      <label for="lastName">@lang('website.Zip/Postal Code')</label>
                      <input type="text" class="form-control field-validate" id="postcode" name="postcode" value="@if(count(session('shipping_address'))>0){{session('shipping_address')->postcode}}@endif">
                      <span class="help-block error-content" hidden>@lang('website.Please enter your Zip/Postal Code')</span> 
                    </div>        
                  </div>    
                  <div class="button"><button type="submit" class="btn btn-dark">@lang('website.Continue')</button></div>
              </form>
            </div>
            <div class="tab-pane fade @if(session('step') == 1) show active in @endif" id="pills-billing" role="tabpanel" aria-labelledby="billing-tab">
              <form name="signup" enctype="multipart/form-data" action="{{ URL::to('/checkout/billing/address')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="firstName">@lang('website.First Name')</label>
                    <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_firstname" name="billing_firstname" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_firstname}}@endif">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>  
                  </div>
                  <div class="form-group col-md-6">
                    <label for="lastName">@lang('website.Last Name')</label>
                    <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_lastname" name="billing_lastname" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_lastname}}@endif">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span> 
                  </div>
                  <div class="form-group col-md-6">
                    <label for="firstName">@lang('website.Company')</label>
                    <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_company" name="billing_company" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_company}}@endif">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your company name')</span> 
                  </div>
                  <div class="form-group col-md-6">
                    <label for="firstName">@lang('website.Address')</label>
                    <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_street" name="billing_street" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_street}}@endif">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="lastName">@lang('website.Country')</label>
                      <select class="form-control same_address_select" id="billing_countries_id"  onChange="getBillingZones();" name="billing_countries_id" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) disabled @endif @else disabled @endif  >
                          <option value=""  >@lang('website.Select Country')</option>
                          @if(count($result['countries'])>0)
                            @foreach($result['countries'] as $countries)
                                <option value="{{$countries->countries_id}}" @if(count(session('billing_address'))>0) @if(session('billing_address')->billing_countries_id == $countries->countries_id) selected @endif @endif >{{$countries->countries_name}}</option>
                            @endforeach
                          @endif
                      </select>
                      <span class="help-block error-content" hidden>@lang('website.Please select your country')</span> 
                  </div>
                  <div class="form-group col-md-6">
                    <label for="firstName">@lang('website.State')</label>
                    <select class="form-control same_address_select" id="billing_zone_id" name="billing_zone_id" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) disabled @endif @else disabled @endif  >
                          <option value="" >@lang('website.Select State')</option>
                          @if(count($result['zones'])>0)
                            @foreach($result['zones'] as $key=>$zones)
                                <option value="{{$zones->zone_id}}" @if(count(session('billing_address'))>0) @if(session('billing_address')->billing_zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                            @endforeach                        
                          @endif
                            <option value="Other" @if(count(session('billing_address'))>0) @if(session('billing_address')->billing_zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>
                      </select>
                      <span class="help-block error-content" hidden>@lang('website.Please select your state')</span> 
                  </div>
                  <div class="form-group col-md-6">
                    <label for="lastName">@lang('website.City')</label>
                    <input type="text" class="form-control same_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_city" name="billing_city" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_city}}@endif">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your city')</span>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="lastName">@lang('website.Zip/Postal Code')</label>
                    <input type="text" class="form-control same_address"  @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_zip" name="billing_zip" value="@if(count(session('billing_address'))>0){{session('billing_address')->billing_zip}}@endif">
                    <span class="help-block error-content" hidden>@lang('website.Please enter your Zip/Postal Code')</span> 
                  </div>        
                </div>      
                <div class="form-group">
                    <div class="form-check">
                      <label class="form-check-label">
                          <input  class="form-check-input" id="same_billing_address" value="1" type="checkbox" name="same_billing_address" @if(count(session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) checked @endif @else checked  @endif > @lang('website.Same shipping and billing address')
                      </label>
                    </div>
                </div>
                <div class="button"><button type="submit" class="btn btn-dark"> @lang('website.Continue')</button></div>
          </form>
      </div>
       <div class="tab-pane fade @if(session('step') == 2) show active in @endif" id="pills-order" role="tabpanel" aria-labelledby="order-tab"> 
        <div class="order-review">
          <?php 
              $price = 0;
          ?>
          <form method='POST' id="update_cart_form" action='{{ URL::to('/place_order')}}' >
             <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
              <div class="table-responsive">
                  <table class="table">
                      <thead>
                          <tr>
                              <th align="left">@lang('website.items')</th>
                              <th align="right">@lang('website.Price')</th>
                              <th align="right">@lang('website.Qty')</th>
                              <th align="right">@lang('website.SubTotal')</th>
                          </tr>
                      </thead>
                    
                      @foreach( $result['cart'] as $products)
                      <?php 
                          $price+= $products->final_price * $products->customers_basket_quantity;         
                      ?>
                      <tbody>
                          <tr>
                            <td align="left" class="item">
                                <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="cart-thumb">
                                    <?php
                                        $image = $products->image;
                                        if( isset($products->customers_basket_attributes->image) )
                                        {
                                           $image = $products->customers_basket_attributes->image;
                                        }
                                    ?>
                                    <img class="img-fluid" src="{{getFtpImage($image)}}" alt="{{$products->products_name}}" alt="">
                                </a>
                                <div class="cart-product-detail">
                                    <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="title">
                                        {{$products->products_name}} {{$products->model}}
                                    </a>
                                    @if(count($products->attributes) >0)
                                        <ul>
                                            @foreach($products->attributes as $attributes)
                                                <li>{{$attributes->attribute_name}}<span>{{$attributes->attribute_value}}</span></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </td>
                          
                            <td align="right" class="price"><span>{{$web_setting[19]->value}}{{$products->final_price+0}}</span></td>
                            <td align="right" class="Qty"><span>{{$products->customers_basket_quantity}}</span></td>
                        
                            <td align="right" class="subtotal"><span class="cart_price_{{$products->customers_basket_id}}">{{$web_setting[19]->value}}{{$products->final_price * $products->customers_basket_quantity}}</span>
                            </td>
                          </tr> 
                          <tr>
                              <td colspan="4" class="buttons">
                                  <a href="{{ URL::to('/editcart?id='.$products->customers_basket_id)}}" class="btn btn-sm btn-secondary">@lang('website.Edit')</a>
                                  <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}" class="btn btn-sm btn-secondary">@lang('website.Remove Item')</a>
                              </td>
                          </tr> 
                      </tbody>            
                      @endforeach
                  </table>
              </div>                   
              <?php     
                  if(!empty(session('shipping_detail')) and count(session('shipping_detail'))>0){
                      $shipping_price = session('shipping_detail')->shipping_price;
                      $shipping_name = session('shipping_detail')->mehtod_name;
                  }else{
                      $shipping_price = 0;
                      $shipping_name = '';
                  }       
                  $tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
                  $coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');        
                  $total_price = ($price+$tax_rate+$shipping_price)-$coupon_discount;         
              ?>
          </form>
        </div>
        <div class="notes-summary-area">
          <div class="heading">
                <h2>@lang('website.orderNotesandSummary')</h2>
                <hr>
            </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6 order-notes">
                <p class="title">@lang('website.Please write notes of your order')</p>
                  <div class="form-group">
                      <p for="order_comments"></p>
                      <textarea name="comments" id="order_comments" class="form-control" placeholder="Order Notes">@if(!empty(session('order_comments'))){{session('order_comments')}}@endif</textarea>
                  </div>
              </div>

            <div class="col-xs-12 col-sm-6 order-summary">
              <div class="table-responsive">
                  <table class="table">
                      <tbody>
                          <tr>
                              <th><span>@lang('website.SubTotal')</span></th>
                              <td align="right" id="subtotal">{{$web_setting[19]->value}}{{$price+0}}</td>
                          </tr>
                          <tr>
                              <th><span>@lang('website.Tax')</span></th>
                              <td align="right">{{$web_setting[19]->value}}{{$tax_rate}}</td>
                          </tr>
                          <tr>
                              <th><span>@lang('website.Shipping Cost')</br><small>{{$shipping_name}}</small></span></th>
                              <td align="right">{{$web_setting[19]->value}}{{$shipping_price}}</td>
                          </tr>
                          <tr>
                              <th><span>@lang('website.Discount(Coupon)')</span></th>
                              <td align="right" id="discount">{{$web_setting[19]->value}}{{number_format((float)session('coupon_discount'), 2, '.', '')+0}}</td>
                          </tr>
                          <tr>
                              <th class="last"><span>@lang('website.Total')</span></th>
                              <td class="last" align="right" id="total_price">{{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')+0}}</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
            </div> 
          </div>
        </div>
          <!-- payment-area -->
        <div class="payment-area">
          <input id="stripe_public_key" type="hidden" name="public_key" value="{{$result['payments_setting']->publishable_key}}">
          <input id="stripe_enviroment" type="hidden" name="stripe_enviroment" value="{{$result['payments_setting']->stripe_enviroment}}">
          <!--Shiping mehod from here-->
          <div class="button">
            <button id="stripe_button" class="btn btn-dark payment_btns"  data-toggle="modal" data-target="#stripeModel" >@lang('website.Order Now')</button>
          </div>
        </div>
        <!-- payment-area colsed -->
        <!-- The stripe Modal -->
        <div class="modal fade" id="stripeModel"  role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Enter your card details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <div class="modal-body">
                  <div class="cell example example2">
                    <form>
                      <div class="row">
                        <div class="field">
                          <div id="example2-card-number" class="input empty"></div>
                          <label for="example2-card-number" data-tid="elements_examples.form.card_number_label">@lang('website.Card number')</label>
                          <div class="baseline"></div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="field half-width">
                          <div id="example2-card-expiry" class="input empty"></div>
                          <label for="example2-card-expiry" data-tid="elements_examples.form.card_expiry_label">@lang('website.Expiration')</label>
                          <div class="baseline"></div>
                        </div>
                        <div class="field half-width">
                          <div id="example2-card-cvc" class="input empty"></div>
                          <label for="example2-card-cvc" data-tid="elements_examples.form.card_cvc_label">@lang('website.CVC')</label>
                          <div class="baseline"></div>
                        </div>
                      </div>
                      <div class="error" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                            <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                            <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                        </svg>
                        <span class="message"></span>
                      </div>
                      <div class="row">
                        <button type="submit" class="btn btn-dark" data-tid="elements_examples.form.pay_button">
                          @lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}
                          <span class="spinner-border hide" ></span>
                        </button>
                      </div>
                      <div class="row">
                        <a  class="btn btn-primary  reset" style="margin: 40px 15px 0;width: calc(100% - 30px)" role="button"> Reset </a>
                      </div>
                    </form>
                    <div class="success" style="display: none;">
                      <div class="icon">
                        <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                          <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
                        </svg>
                      </div>
                      <h3 class="title" data-tid="elements_examples.success.title">@lang('website.Payment successful')</h3>
                      <p class="message"><span data-tid="elements_examples.success.message">@lang('website.Thanks You Your payment has been processed successfully')</p>
                    </div>
  
                  </div>
              </div>
                   
            </div>
          </div>
        </div>
      </div>
      </div>
      </div> <!--CHECKOUT LEFT CLOSE-->
          <div class="col-12 col-lg-4 checkout-right">    
            <div class="order-summary-outer">
              <div class="order-summary">
                    <div class="table-responsive">
                        <table class="table">
                          <thead>
                              <tr>
                                  <th colspan="2">@lang('website.Order Summary') </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th><span>@lang('website.SubTotal')</span></th>
                                    <td align="right" id="subtotal">{{$web_setting[19]->value}}{{$price+0}}</td>
                                </tr>
                                <tr>
                                    <th><span>@lang('website.Tax')</span></th>
                                    <td align="right">{{$web_setting[19]->value}}{{$tax_rate}}</td>
                                </tr>
                                <tr>
                                    <th>
                                      <span>@lang('website.Shipping Cost')</br><small>{{$shipping_name}}</small></span></span></th>
                                    <td align="right">{{$web_setting[19]->value}}{{$shipping_price}}</td>
                                </tr>
                                <tr>
                                    <th><span>@lang('website.Discount(Coupon)')</span></th>
                                    <td align="right" id="discount">{{$web_setting[19]->value}}{{number_format((float)session('coupon_discount'), 2, '.', '')+0}}</td>
                                </tr>
                                <tr>
                                    <th class="last"><span>@lang('website.Total')</span></th>
                                    <td class="last" align="right" id="total_price">{{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')+0}}</td>
                                </tr>
                          </tbody>
                        </table>
                    </div>
                </div> 
                <div class="coupons">
                  <!-- applied copuns -->
                  @if(count(session('coupon')) > 0 and !empty(session('coupon')))
                    <div class="form-group"> 
                          <label>@lang('website.Coupon Applied')</label>         
                          @foreach(session('coupon') as $coupons_show)  
                                  
                              <div class="alert alert-success">
                                  <a href="{{ URL::to('/removeCoupon/'.$coupons_show->coupans_id)}}" class="close"><span aria-hidden="true">&times;</span></a>
                                  {{$coupons_show->code}}
                              </div>
                              
                          @endforeach
                      </div>    
                    @endif  
                    <form id="apply_coupon">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="form-group">
                            <label for="inputPassword2" class="">@lang('website.Coupon Code')</label>
                            <input type="text" name="coupon_code" class="form-control" id="coupon_code">
                        </div>
                        <button type="submit" class="btn btn-sm btn-dark">@lang('website.ApplyCoupon')</button>
                        <div id="coupon_error" style="display: none"></div>
                        <div id="coupon_require_error" style="display: none">@lang('website.Please enter a valid coupon code')</div>
                    </form>
                </div>
            </div>  
        </div>  <!--CHECKOUT RIGHT CLOSE-->
      </div>
    </div>
  </div>
</section>
  
   


@endsection   


