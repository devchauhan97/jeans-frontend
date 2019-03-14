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
            <div class="row">
                <div class="offset-md-3 col-md-6">
                    <form name="updateprofile" class="form-validate" enctype="multipart/form-data" action="{{ URL::to('updateprofile')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="login-page user-profile">
                        <div class="left">
                            <img class="width-100 img-fluid" style="object-fit: cover" src="images/login-img.jpg">

                        </div>
                        <div class="right">
                            <div class="row text-center">
                                <div class="col-sm-12">
                                    @if(!empty(auth()->guard('customer')->user()->customers_picture))
                                        <a class="user-box" ><img src="{{getFtpImage(auth()->guard('customer')->user()->customers_picture)}}" class="upload-preview">
                                        </a>
                                        <input type="hidden" name="customers_old_picture" value="{{ auth()->guard('customer')->user()->customers_picture }}">
                                    @else
                                    <a class="user-box" >
                                        <img class="upload-choose-icon" src="{{asset('images/user-img1.png')}}" />
                                    </a>
                                    @endif
                                    <input name="picture" id="userImage" type="file" class="inputFile" onChange="showPreview(this);" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="login-form">
                                        @if( count($errors) > 0)
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
                                        @endif 
                                        @if(session()->has('error'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session()->get('error') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @endif

                                        <!-- <label for="uname"><b>Username</b></label> -->
                                        <input type="text"   name="customers_firstname" class="form-control field-validate" placeholder="@lang('website.First Name')" id="firstName" value="{{ auth()->guard('customer')->user()->customers_firstname }}">
                                        <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>

                                        <!-- <label for="psw"><b>Password</b></label> -->
                                        <input type="text"   name="customers_lastname" placeholder="@lang('website.Last Name')" class="form-control" id="lastName" value="{{ auth()->guard('customer')->user()->customers_lastname }}">

                                        <input type="text" placeholder="DOB" name="customers_dob" type="text" class="form-control" id="datepicker" placeholder="@lang('website.Date of Birth')" value="{{ auth()->guard('customer')->user()->customers_dob }}">
                                        <span class="help-block error-content" hidden>@lang('website.Please enter your date of birth.')</span>

                                        <input type="text" placeholder="Mobile No." name="customers_telephone" type="tel" class="form-control " id="phone"   value="{{ auth()->guard('customer')->user()->customers_telephone }}">
                                        <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span>
                                        <div class="spacer-30"></div>
                                        <button type="submit">Update</button>
                                    </div>
                                </div>
                                <div class="spacer-30"></div>
                            </div>
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
    
    function phone_formatting(ele,restore) {
  var new_number,
      selection_start = ele.selectionStart,
      selection_end = ele.selectionEnd,
      number = ele.value.replace(/\D/g,'');
  
  // automatically add dashes
  if (number.length > 2) {
    // matches: 123 || 123-4 || 123-45
    new_number = number.substring(0,3) + '-';
    if (number.length === 4 || number.length === 5) {
      // matches: 123-4 || 123-45
      new_number += number.substr(3);
    }
    else if (number.length > 5) {
      // matches: 123-456 || 123-456-7 || 123-456-789
      new_number += number.substring(3,6) + '-';
    }
    if (number.length > 6) {
      // matches: 123-456-7 || 123-456-789 || 123-456-7890
      new_number += number.substring(6);
    }
  }
  else {
    new_number = number;
  }
  
  // if value is heigher than 12, last number is dropped
  // if inserting a number before the last character, numbers
  // are shifted right, only 12 characters will show
  ele.value =  (new_number.length > 12) ? new_number.substring(0,12) : new_number;
  
  // restore cursor selection,
  // prevent it from going to the end
  // UNLESS
  // cursor was at the end AND a dash was added
  document.getElementById('msg').innerHTML='<p>Selection is: ' + selection_end + ' and length is: ' + new_number.length + '</p>';
  
  if (new_number.slice(-1) === '-' && restore === false
      && (new_number.length === 8 && selection_end === 7)
          || (new_number.length === 4 && selection_end === 3)) {
      selection_start = new_number.length;
      selection_end = new_number.length;
  }
  else if (restore === 'revert') {
    selection_start--;
    selection_end--;
  }
  ele.setSelectionRange(selection_start, selection_end);

}
  
function phone_number_check(field,e) {
  var key_code = e.keyCode,
      key_string = String.fromCharCode(key_code),
      press_delete = false,
      dash_key = 189,
      delete_key = [8,46],
      direction_key = [33,34,35,36,37,38,39,40],
      selection_end = field.selectionEnd;
  
  // delete key was pressed
  if (delete_key.indexOf(key_code) > -1) {
    press_delete = true;
  }
  
  // only force formatting is a number or delete key was pressed
  if (key_string.match(/^\d+$/) || press_delete) {
    phone_formatting(field,press_delete);
  }
  // do nothing for direction keys, keep their default actions
  else if(direction_key.indexOf(key_code) > -1) {
    // do nothing
  }
  else if(dash_key === key_code) {
    if (selection_end === field.value.length) {
      field.value = field.value.slice(0,-1)
    }
    else {
      field.value = field.value.substring(0,(selection_end - 1)) + field.value.substr(selection_end)
      field.selectionEnd = selection_end - 1;
    }
  }
  // all other non numerical key presses, remove their value
  else {
    e.preventDefault();
//    field.value = field.value.replace(/[^0-9\-]/g,'')
    phone_formatting(field,'revert');
  }

}

document.getElementById('phone').onkeyup = function(e) {
  phone_number_check(this,e);
}

</script>
@endsection 	


