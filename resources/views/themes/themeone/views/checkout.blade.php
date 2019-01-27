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
            <div class="">
				        <div class="col-12 col-lg-10 checkout-left">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item @if(session('step')==0) active @endif">
                            <a class="nav-link @if(session('step')==0) active @elseif(session('step')>0) active-check @endif" id="shipping-tab" data-toggle="pill" href="#pills-shipping" role="tab" aria-controls="pills-shpping" aria-expanded="true">@lang('website.Shipping Address')</a>
                        </li>
                        <li class="nav-item @if(session('step')==1) active @endif">
                            <a class="nav-link @if(session('step')==1) active @elseif(session('step')>1) active-check @endif" @if(session('step')>=1) id="billing-tab" data-toggle="pill" href="#pills-billing" role="tab" aria-controls="pills-billing" aria-expanded="true" @endif >@lang('website.Billing Address')</a>
                        </li>
                        
                        <li class="nav-item @if(session('step')==2) active @endif">
                            <a class="nav-link @if(session('step')==2) active @elseif(session('step')>2) active-check @endif"  @if(session('step')>=3)  id="order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-expanded="true"  @endif>@lang('website.Order Detail')</a>
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
                          <input type="hidden" name="_token" value="{{csrf_token()}}">
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
                                <input type="text" class="form-control field-validate" id="company" name="company" value="@if(count(session('shipping_address'))>0) {{session('shipping_address')->company}}@endif">
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
                          <input type="hidden" name="_token" value="{{csrf_token()}}">
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
                      <div class="row"> 
                        <div class="col-md-8">
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
                                                      <img class="img-fluid" src="{{getFtpImage($products->image)}}" alt="{{$products->products_name}}" alt="">
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
                        <div class="col-md-4 checkout-right">    
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
                                  <input type="hidden" name="_token" value="{{csrf_token()}}">
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

                    <div class="row">
                      <div class="notes-summary-area">
                          <div class="heading">
                          <h2>@lang('website.orderNotesandSummary')</h2>
                          <hr>
                      </div>
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
                          <form action="." method="post">
<noscript>You must <a href="http://www.enable-javascript.com" target="_blank">enable JavaScript</a> in your web browser in order to pay via Stripe.</noscript>
<input
type="submit"
value="Pay with Card"
data-key="pk_test_LL4ootDDzuctxEyl4wnYfoW5"
data-amount="500"
data-currency="cad"
data-name="Example Company Inc"
data-description="Stripe payment for $5"
/></form>
                      </div>
                    </div> 
                  </div>
                  @include('paymentComponent')
                </div>
              </div>
            </div>
          </div>
        </div> <!--CHECKOUT LEFT CLOSE-->
      </div>
    </div>
	</div>
</section>


@endsection 	


