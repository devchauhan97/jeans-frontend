@extends('layouts')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> <!-- Popper plugin for Bootstrap -->
 <style>
    .orderDetail {
        font-family: 'Arial, Helvetica', sans-serif;
        margin: 50px 0 0 0;
        width: 100%;
        background: #c4c3c1;
        border-radius: 4px;
        padding: 15px 0 1px;;
    }
    .orderDetail p {
        font-size: 16.41px;
        color: #000;
        padding-left: 15px;
    }
    .orderDetail p span {
        font-weight: 600;
    }
    .row {
        margin-left: -15px;
        margin-right: -15px
    }
    .line {
        width: 100%;
        height: 1px;
        background: #000;
        clear: both;
        float: left;
        margin: 18px 0;
    }
    .heading {
        font-family: 'Arial, Helvetica', sans-serif;
        margin: 48px 0 25px 0;
        font-size: 21.88px;
        text-align: left;
        font-weight: 600;
    }
    .formgroup {
        font-family: 'Arial, Helvetica', sans-serif;
        width: 100%;
        background: #f5f5f1;
        padding: 9px 0;
        border-radius: 4px;
        border: #deded9 solid 1px;
        box-sizing: border-box;
    }
    .formgroup img {
        padding-right: 5px;
        padding-left: 5px;
        position: relative;
        top: -1.5px;
    }
    .formgroup label {
        color: #8e979b;
        font-size: 15.5px;
    }
    input {
        padding-left: 12px;
    }
    .formgroup a {
        color: #8e979b;
        text-decoration: none;
    }
    .app-payment {
        width: 576px;
        max-width: 100%;
        margin: auto;
        border: solid red 0px;
    }
    .marginTop {
        margin-top: -6px!important
    }
    .app-box .radio {
        margin-top: 5px;
    }
    .shadow {
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    }
    .app-box {
        width: calc(100% - 16px);
        border-radius: 20px;
        border: solid #000 1px;
        margin: 8px;
        font-size: 16px;
        padding: 14px 0px 5px;
        box-sizing: border-box;
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    }
    .formgroup {
        margin-bottom: 15px;
        max-width: 100%;
        padding-left: 15px;
    }       
    .text-center {
        text-align: center;
    }
    .btn-pay {
        font-size: 41px;
        border-radius: 15px;
        background: #000;
        padding: 4px 70px;
        color: #fff;
        margin: auto;
        cursor: pointer;
        box-shadow: 0 2px #999;
    }
    .btn-pay:hover, .btn-pay:focus {    
        box-shadow: 0 2px #999;
    }   
        
    .plus {
        font-size: 35.5px;
        color: #000;
        font-weight: 600;
        font-family: 'Arial, Helvetica', sans-serif;
    }
    .newcard {
        font-family: 'Arial, Helvetica', sans-serif;
        width: 100%;
        background: #f5f5f1;
        padding: 9px 40px 30px 25px;
        border-radius: 4px;
        border: #deded9 solid 1px;
        box-sizing: border-box;
        margin-bottom: 50px;
    }
    .newcard p {
        color: #555555;
        font-size: 18px;
        margin: 20px 0;
        font-weight: 600;
    }
    .newcard [type='text'] ,.newcard [type='number']{
        width: 100%;
        border: #70706d solid 1px;
        border-radius: 4px;
        min-height: 50px;
        margin-bottom: 10px;
        font-size: 16px;
    }
    .bold {
        font-size: 16px;
        font-weight: 600;
        color: #606161;
    }
    .newcard [type="checkbox"] {
        margin: 10px 10px 10px 0px;
    }
    .newcard [type="checkbox"]:not(:checked),  .newcard [type="checkbox"]:checked {
        position: absolute;
        left: -9999px;
    }
    .newcard [type="checkbox"]:not(:checked) + label,  .newcard [type="checkbox"]:checked + label {
        position: relative;
        padding-left: 25px;
        cursor: pointer;
    }
    /* checkbox aspect */
    .newcard [type="checkbox"]:not(:checked) + label:before,  .newcard [type="checkbox"]:checked + label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 2px;
        width: 17px;
        height: 17px;
        border: 2px solid #000;
        background: #f8f8f8;
        border-radius: 0px;
    }
    .newcard [type="checkbox"]:not(:checked) + label:after,  .newcard [type="checkbox"]:checked + label:after {
        content: '✔';
        position: absolute;
        top: -14px;
        left: 0px;
        font-size: 25px;
        color: #000;
        line-height: 1.75;
        -webkit-transition: all .2s;
        -moz-transition: all .2s;
        -ms-transition: all .2s;
        transition: all .2s;
        font-weight: 400;
    }
    .newcard [type="checkbox"]:not(:checked) + label:after {
        opacity: 0;
        -webkit-transform: scale(0);
        -moz-transform: scale(0);
        -ms-transform: scale(0);
        transform: scale(0);
    }
    .newcard [type="checkbox"]:checked + label:after {
        opacity: 1;
        -webkit-transform: scale(1);
        -moz-transform: scale(1);
        -ms-transform: scale(1);
        transform: scale(1);
    }
    .newcard [type="checkbox"]:disabled:not(:checked) + label:before,  [type="checkbox"]:disabled:checked + label:before {
        box-shadow: none;
        border-color: #bbb;
        background-color: #ddd;
    }
    .newcard [type="checkbox"]:disabled:checked + label:after {
        color: #999;
    }
    .newcard [type="checkbox"]:disabled + label {
        color: #aaa;
    }
    .newcard [type="checkbox"]:checked:focus + label:before,  [type="checkbox"]:not(:checked):focus + label:before {
        border: 2px solid #000;
    }
        
    .newcard input[type=number]::-webkit-inner-spin-button, 
    .newcard input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
    .newcard input[type='number'] {
        -moz-appearance:textfield;
    }
     .header{
            width: 100%;
            background: #f5f5f1;
            padding: 18px 0;
            border-radius: 4px;
            border: #deded9 solid 1px;
            box-sizing: border-box;
            margin-bottom: 10px;
            color: #8e979b;
            padding-left: 7px;
            cursor: pointer;    
            font-size: 16px;            
        }
    .header img{
        position: relative;
        top:-2px;
        padding-right: 10px;
    }
    .containerone .content {
        display: none;   
    }
    </style>
    <style type="text/css">
          #namanyay-search-btn {
            right: 25px;
            top: 0px;
          }
          .easy-autocomplete-container li:nth-child(even) {
            background-color: lightgrey;
          }
        </style>
 
<!-- Site Content -->
<section class="content main-container" id="site-content"><!-- Site Page Header -->
<section class="page-header" style="height: 120px;">
</section>
<!-- Site Content -->
<section class="content main-container" id="site-content">
    <div class="ptb-40">
        <div class="container">
            
            <div class="row">
                <div class="clearfix"></div>
                <br>
                <div class="col-sm-10 col-md-6 offset-md-3 offset-sm-1">
                <?php
                    $price=0;
                    foreach( $result['cart'] as $products)
                    $price+= $products->final_price * $products->customers_basket_quantity;

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
                <div class="orderDetail">
                    <p><span>Order ID:</span> #{{session()->get('unique_order_id')}}</p>  
                    <p><span>Grand Total:</span> {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')+0}} </p>
                </div>

                <div class="row">
                    <div class="line"></div>
                </div>
                <div class="row">
                    @if( count($errors) > 0)
                      @foreach($errors->all() as $error)
                      <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">@lang('website.Error'):</span>
                          {{ $error }}
                      </div>
                      @endforeach
                    @endif
                </div>
                @if(count($result['data_key']))
                    @php $card_type_img = ['M' => 'master.png', 'V' => 'payment-visa.png', 'AX' => 'American-Express.png', 'NO' => '', 'DS' => '', 'C' => '', 'C1' => '', 'SE' => '', 'CQ' => '', 'P' => '', 'D' => ''] @endphp
                    <div class="heading">Select Credit Cards </div> 

                    <div class="alert alert-danger alertpay" role="alert" style="display: none;"></div>
                    @foreach($result['data_key'] as $dk_key => $dk_val)
                    <form action="{{ route('savedCardPayment') }}" method="POST" id="paymentForm{{ $dk_key }}" class="paymentForm1">
                         <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="card_id" value="{{ $dk_val->id }}">
                        <input type="hidden" name="order_id" value="{{ @$orders_data->orders_id }}" >
                        <div class="formgroup">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="pan" class="cardnumber" value="{{ $dk_val->MaskedPan }}" id="{{ $dk_key }}" onclick="check_r('{{ $dk_key }}')" {{ !$dk_key ? 'checked' : '' }}>
                                    @if(!empty($dk_val->CardType) && @getimagesize(asset('public/img/' . $card_type_img[$dk_val->CardType])))<span><img src="{{ asset('public/img/' . $card_type_img[$dk_val->CardType]) }}"></span>@endif {{ $dk_val->MaskedPan }} 
                                </label>
                            </div>
                        </div>                
                    </form>
                    @endforeach
                @endif

                    <div class="containerone">
                        <div class="header" id="headerp"><img src=" @if(count( $result['data_key'] )) {{ asset('/images/plus.png') }} @else {{ asset('/images/minus.png') }} @endif"> Use Another Card</div>
                        <div class="content" id="form-content2" style="display: @if(count( $result['data_key'] )) none @else block @endif">
                            <div class="newcard">
                                <p> Use New Card</p>
                                <form action="{{ route('newCardPayment') }}" method="post" id="paymentForm">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="order_id" value="{{ @$orders_data->orders_id }}" >
                                    <input type="text" class="avsinfo" name="avs_street_number" id="avs_street_number" value="" placeholder="Street Number *" required="" style="">
                                    <input type="text" class="avsinfo" name="avs_street_name" id="avs_street_name" value="" placeholder="Street Name *" required="" style="">
                                    <input type="text" class="avsinfo" name="avs_zipcode" value="" id="avs_zipcode" placeholder="Postal Code *" required="" style="">                     
                                    <input type="number" name="pan" id="pan" value="" required="required" placeholder="Card Number *">
                                    <input type="number" name="expiry_date" id="expiry_date" value="" required="required" placeholder="Expiry Date (MMYY) *">
                                    <input type="number" name="cvd_value" id="cvd_value" value="" required="required" placeholder="CVD *" maxlength="3" minlength="3">
                                    <br>
                                    <input type="checkbox" id="cardAccptance" name="cardAccptance" value="Yes">
                                    <label for="cardAccptance" class="bold"> Save Card for Future Use</label>   
                                </form>                 
                            </div>
                        </div>
                    </div> 
                    <div class="text-center">
                        <button type="button" class="btn-pay" id="btn-pay">Pay</button>
                    </div>
                </div>     
                <div class="clearfix"></div>
            </div>
            
        </div>
    </div>
</section>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js">
     
 </script>
 
<script>
jQuery(document).ready(function(){
    jQuery("#headerp").click(function () {
        $header = jQuery(this);   
        $content = $header.next();    
        $content.slideToggle(500, function () {       
            $header.text(function () {  
                if($content.is(":visible")==true){
                    jQuery(".alertpay").empty().hide();
                    jQuery(".cardnumber").prop("checked", false);
                    jQuery(this).html('<img src="{{ asset('images/minus.png') }}" /> Use Another Card');
                }else{
                    jQuery(this).html('<img src="{{ asset('images/plus.png') }}" /> Use Another Card');
                }
            });
        });
    });

    jQuery(".cardnumber").click(function () {
        $header = jQuery("#headerp");   
        $content = $header.next();    
        $content.hide(); 
        jQuery(".alertpay").empty().hide();  
        $header.text(function () {  
            if($content.is(":visible")==true){
                jQuery(".cardnumber").prop("checked", false);
                jQuery(this).html('<img src="{{ asset('images/minus.png') }}" /> Use Another Card');
            }else{
                jQuery(this).html('<img src="{{ asset('images/plus.png') }}" /> Use Another Card');
            }
        });
        
    });

    jQuery("#btn-pay").click(function () {
       
        $header = jQuery("#headerp");   
        $content = $header.next(); 
        if($content.is(":visible") == true){
            var $cur = jQuery("#paymentForm"); 
            $cur.validate();



            if($cur.valid()) {
                jQuery(this).prop('disabled', true).text("Please wait...");
                jQuery("#paymentForm").submit();
            }
            else{
                jQuery(this).prop('disabled', false).text("Pay");
            }
        }
        else{
            var radio_btn = jQuery(".cardnumber:checked");
            if(radio_btn.length){
                jQuery(this).prop('disabled', true).text("Please wait...");
                radio_btn.closest("form").submit();
                jQuery(".alertpay").empty().hide();
            }
            else{
                jQuery(this).prop('disabled', false).text("Pay");
                jQuery(".alertpay").text("Please select a card.").show();
                return false;
            }
        }
    });

    jQuery('.avsinfo').hide();   
    jQuery('#cardAccptance').click(function(){
        if(jQuery(this).prop("checked") == true){                
            jQuery('.avsinfo').show();
            jQuery('.avsinfo input').attr('required', true);
        }
        else if(jQuery(this).prop("checked") == false){
            jQuery('.avsinfo').hide();
            jQuery('.avsinfo input').attr('required', false);
        }
        jQuery("#paymentForm").validate();
    });

});

function check_r(obj) {
    jQuery('.cardnumber').prop('checked', false);;
    jQuery('#' + obj).prop('checked', true);
    jQuery('.proc_btn').hide();
    jQuery('#bt' + obj).show();
}
</script>
@endsection