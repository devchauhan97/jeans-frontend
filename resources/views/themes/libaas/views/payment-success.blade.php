@extends('layouts')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> 
<!-- Popper plugin for Bootstrap -->
<!-- Site Content -->
<section class="page-header" style="height: 120px;">
</section>
 <!-- Site Content -->
<section class="content main-container" id="site-content">
    <div class="ptb-40">
        <div class="container">
            <div class="row">
                <div class="offset-md-3 col-md-6">
                    <div class="thanks-page-out">
                        <div class="thanks-page">
                            <div class="thanks-page-in">
                                <h2>Thank You <br>for Your Order!</h2>
                                <p>Order Id #{{$result['unique_order_id']}} </p>
                                <img src="{!! asset('images/smile-icon.png') !!}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>

</section> 
@endsection