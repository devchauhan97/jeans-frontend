 
<div class="col-md-6 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">Paywith Stripe</div>
          <form id="stript_from">
            {{ csrf_field() }}
              <div class="panel-body">

                <div class="row form-group">
                    <label for="card_no" class="col-md-4 control-label">Card No</label>
                    <div class="col-md-6">
                      <input id="card_no" type="text" class="form-control" name="card_no" value="{{ old('card_no') }}" autofocus placeholder="4000056655665556" >
                        <span class="help-block error-content">
                          
                        </span>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="ccExpiryMonth" class="col-md-4 control-label">Expiry Month</label>
                    <div class="col-md-6">
                        {!! Form::selectMonth('cc_expiry_month',old('cc_expiry_month'),['class' => 'form-control','id' => 'cc_expiry_month']) !!}
                        <span class="help-block error-content" >
                        </span>
                         
                    </div>
                </div>
                <div class="row form-group">
                    <label for="ccExpiryYear" class="col-md-4 control-label">Expiry Year</label>
                    <div class="col-md-6">
                      
                      {{Form::selectYear('cc_expiry_year',$result['curr_year'], $result['curr_year']+ 10,null,['class' => 'form-control','id' => 'cc_expiry_year'])}}
                        <span class="help-block error-content" >
                        </span>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="cvvNumber" class="col-md-4 control-label">CVV No.</label>
                    <div class="col-md-6">
                        <input id="cvv_number" type="text" class="form-control" name="cvv_number" value="{{ old('cvv_number') }}" autofocus >
                        <span class="help-block error-content">
                        </span>
                    </div>
                </div>
                <!-- <div class="row form-group">
                    <label class="col-md-4 control-label">Amount</label>
                   <div class="col-md-6">
                        <div class="input-group-prepend"><span class="input-group-text">$</span></div> 
                        <div class="input-group-append"><span class="input-group-text"> </span></div>
                    </div>
                </div> -->
          </form>
          <div class="row form-group">
            <div class="col-md-6 col-md-offset-4">
              <div class="alert alert-danger stripe-error" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <span></span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <a   class="btn btn-primary" id="stripe_btn">
                    @lang('website.Order Now')
                </a>
            </div>
          </div>
        </div>
    </div>
</div>
 