@extends('layouts')
 
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> <!-- Popper plugin for Bootstrap -->
<!-- Site Page Header -->
<section class="page-header" style="height: 120px;">
</section>
  <!-- Site Content -->
  <section class="content main-container" id="site-content">
    <div class="ptb-40">
      <div class="container">
        <div class="password-change">
          <h3>Change Password</h3>
          <!-- @if( count($errors) > 0)
              @foreach($errors->all() as $error)
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <span class="sr-only">@lang('website.Error'):</span>
                      {{ $error }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              @endforeach
          @endif -->
          @if(session()->has('success') )
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session()->get('success') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
          @endif   
          <div class="password-change-in">
            <form name="updatepassword" class="" enctype="multipart/form-data" action="{{ URL::to('/updatepassword')}}" method="post">
              <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="custom-input">
              <input type="password" placeholder="Old Password" name="old_password" type="password" class="form-control" id="old_password"  value="{{old('old_password')}}">
              <span class="help-block error-content" hidden>@lang('website.Please enter your Old password')</span>
              <small class="text-danger">{{ $errors->first('old_password') }}</small>
            </div>
            <div class="custom-input">
              <input type="password" name="new_password" type="password" class="form-control" id="new_password" placeholder="@lang('website.New Password')"  value="{{old('new_password')}}">
              <span class="help-block error-content" hidden>@lang('website.Please enter your password and should be at least 6 characters long')</span>
              <small class="text-danger">{{ $errors->first('new_password') }}</small>
            </div>
            <div class="custom-input">
              <input type="password" name="confirm_password" type="password" class="form-control" id="confirm_password" placeholder="@lang('website.Confirm Password')"  value="{{old('confirm_password')}}">
              <span class="help-block error-content" hidden>@lang('website.Please enter your Confirm password')</span>
              <small class="text-danger">{{ $errors->first('confirm_password') }}</small>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <a class="btn btn-dark width-100" onclick="chanagePasssword()" >Save</a>
              </div>
              <div class="col-sm-6">
                <a class="btn btn-primary btn-secondary" href="{{URL::to('/')}}"  >Cancel</a>
              </div>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  <div class="clear"></div>
</section>
<script type="text/javascript">
  function chanagePasssword() {
    console.log('chanagePasssword')
    jQuery('[name=updatepassword]').trigger('submit')
  }
</script>
@endsection   


