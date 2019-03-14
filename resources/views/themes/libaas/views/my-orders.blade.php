@extends('layouts')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> 
<!-- Popper plugin for Bootstrap -->
<!-- Site Content -->
<section class="page-header" style="height: 120px;">
</section>
 <!-- Site Content -->
<section class="content main-container" id="site-content">
    <div class="container">
            <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Cart</a></li>
                    <li>Checkout</li>
                </ul>
    </div>
    <div class="ptb-40">
        <div class="container">
            <div class="text-center">
                <h2>My Orders</h2>
                <div class="spacer-30"></div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-10">
                    <div class="white-bg p-50 order-item">

                        @if(count($result['orders']) > 0)
                        @foreach( $result['orders'] as $orders)
                        <div class="cart-box">
                            <div class="cart-product">
                                <div class="row">
                                    <div class="col-sm-3 col-xs-4">
                                        <div class="cart-img box-border bottom-box-shadow">
                                            <img class="" src="{{getFtpImage($orders->products_image)}}" alt="{{$orders->products_name}}" >
                                        </div>
                                            <div class="qty d-lg-none d-md-none d-sm-none  min-421-none" style="display: block;">
                                                @if( $orders->semi_stitched )
                                                <a class="btn" >semi stitched</a>
                                                @endif<br>
                                                <div >
                                                   Qty: {{$orders->products_quantity}}
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-sm-9 col-xs-8">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                    <div class="cart-detail">
                                                            <p><b>{{$orders->products_name}}</b></p>
                                                            <div class="qty d-none-420">
                                                                @if( $orders->semi_stitched )
                                                                <a class="btn" >semi stitched</a>
                                                                @endif
                                                                <div>
                                                                  Qty: {{$orders->products_quantity}}
                                                                </div>
                                                            </div>
                                                            <p>RIF : {{$orders->products_model}}</p>
                                                            <!-- <p>Coral Chanderi silk</p>
                                                            <h3>$995.95</h3> -->
                                                            <!-- <a class="remove" href="#"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</a> -->
                                                        </div>
                                            </div>
                                            <div class="col-sm-6">
                                                    <div class="cart-detail">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                    <h5>Ordered On</h5>
                                                                    <p><span>{{ date('d/m/Y', strtotime($orders->date_purchased))}}</span> </p>
                                                                    <h5>Deliverd On</h5>
                                                                    <p><span>{{ date('d/m/Y', strtotime($orders->deliver_on))}}</span></p>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p><strong>Order#</strong> <span>{{$orders->unique_order_id}}</span></p>
                                                                <a class="btn btn-primary btn-dark" href="{{URL::to('view/order/'.$orders->orders_id)}}">Track Order</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="spacer-40"></div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>

</section>

@endsection
 	


