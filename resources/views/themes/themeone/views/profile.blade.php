@extends('layouts')
@section('customcss')
@if(!empty(session("theme")))
        <link href="{!! asset('css/'.session("theme").'.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @else
        <link href="{!! asset('css/app.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @endif
<link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}">
<link href="{!! asset('css/responsive.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/rtl.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/font-awesome.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/owl.carousel.css') !!} " media="all" rel="stylesheet" type="text/css"/>
 <link href="{!! asset('css/bootstrap-select.css') !!} " media="all" rel="stylesheet" type="text/css"/>
  
@endsection
@section('content')
<section class="site-content">
	<div class="container">
    	<div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.myProfile')</h3>
                <ol class="breadcrumb">
                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    <li class="breadcrumb-item active">@lang('website.myProfile')</li>
                </ol>
            </div>
        </div>

        <div class="registration-area">
            
            
            <div class="row">            	
                <div class="col-12 col-lg-3 spaceright-0">
                    @include('common.sidebar_account')
                </div>
            	<div class="col-12 col-lg-9 new-customers">
                	<div class="col-12 spaceright-0">
                    	<div class="heading">
                            <h2>@lang('website.myProfile')</h2>
                            <hr>
                        </div>
                        
                         <div class="row">
                            <div class="col-sm-12">
                                <form name="updateprofile" class="form-validate" enctype="multipart/form-data" action="{{ URL::to('updateprofile')}}" method="post">

                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
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
                                    
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                            <span class="sr-only">@lang('website.Error'):</span>
                                            {{ session()->get('error') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                            <span class="sr-only">@lang('website.Error'):</span>
                                            {!! session('loginError') !!}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                
                                    @if(session()->has('success') )
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session()->get('success') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif                                    
                                    
                                    <div class="form-group row justify-content-center">						
                                        <div class="uploader">
                                            <h5 class="title-h5">Upload Profile Photo</h5> 
                                            <div class="upload-picture">
                                                <div class="uploaded-image" id="uploaded_image">
                                                @if(!empty(auth()->guard('customer')->user()->customers_picture))
                                                	<img src="{{getFtpImage(auth()->guard('customer')->user()->customers_picture)}}" width="150px" height="150px" class="upload-preview">
                                                    <input type="hidden" name="customers_old_picture" value="{{ auth()->guard('customer')->user()->customers_picture }}">
                                                @else
                                                	<input type="hidden" name="customers_old_picture" value="">
                                                @endif
                                                </div>
                                                <img class="upload-choose-icon" src="{{asset('').'images/default.png'}}" />
                                                <div class="upload-choose-icon">
                                                    <input name="picture" id="userImage" type="file" class="inputFile" onChange="showPreview(this);" />
                                                </div>
                                            </div>   
                                        </div>                
                                    </div>
                                	<h5 class="title-h5">@lang('website.Personal Information')</h5>
                        			<hr class="featurette-divider">
                                    
                                    <div class="form-group row">
                                        <label for="firstName" class="col-sm-4 col-form-label">@lang('website.First Name')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="customers_firstname" class="form-control field-validate" placeholder="@lang('website.First Name')" id="firstName" value="{{ auth()->guard('customer')->user()->customers_firstname }}">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="lastName" class="col-sm-4 col-form-label">@lang('website.Last Name')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="customers_lastname" placeholder="@lang('website.Last Name')" class="form-control" id="lastName" value="{{ auth()->guard('customer')->user()->customers_lastname }}">
                                            
                                        </div>
                                    </div>
                                  
                                    <div class="form-group row">
                                        <label for="gender" class="col-sm-4 col-form-label">@lang('website.Gender')</label>
                                        <div class="col-sm-8">
                                            <select class="custom-select field-validation" name="customers_gender" id="gender">
                                                <option value="Male" @if(auth()->guard('customer')->user()->customers_gender == 'Male') selected @endif>@lang('website.Male')</option>
                                                <option value="Female"  @if(auth()->guard('customer')->user()->customers_gender == 'Female') selected @endif>@lang('website.Female')</option>
                                            </select>
                                            <span class="help-block error-content" hidden>@lang('website.Please select your gender')</span>
                                        </div>                                        
                                    </div>
                                                                 
                                    <div class="form-group row">
                                        <label for="datepicker" class="col-sm-4 col-form-label">@lang('website.Date of Birth')</label>
                                        <div class="col-sm-8">
                                            <input readonly name="customers_dob" type="text" class="form-control" id="datepicker" placeholder="@lang('website.Date of Birth')" value="{{ auth()->guard('customer')->user()->customers_dob }}">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your date of birth.')</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-4 col-form-label">@lang('website.Phone Number')</label>
                                        <div class="col-sm-8">
                                            <input name="customers_telephone" type="tel" class="form-control " id="phone" placeholder="@lang('website.Phone Number')" value="{{ auth()->guard('customer')->user()->customers_telephone }}">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span>
                                        </div>
                                    </div>
                                    <div class="button">
                                        <button type="submit" class="btn btn-dark">@lang('website.Update')</button>
                                    </div>
                                </form>
                                                                
                                <h5 class="title-h5" style="margin-top:30px;">@lang('website.Change Password')</h5>
                                <hr class="featurette-divider">
                                <form name="updatepassword" class="" enctype="multipart/form-data" action="{{ URL::to('/updatepassword')}}" method="post">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <!-- <div class="form-group row">
                                        <label for="old_password" class="col-sm-4 col-form-label">@lang('website.Old Password')</label>
                                        <div class="col-sm-8">
                                            <input name="old_password" type="password" class="form-control" id="old_password" placeholder="@lang('website.Old Password')">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your old password and should be at least 6 characters long')</span>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label for="new_password" class="col-sm-4 col-form-label">@lang('website.New Password')</label>
                                        <div class="col-sm-8">
                                            <input name="new_password" type="password" class="form-control" id="new_password" placeholder="@lang('website.New Password')">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your password and should be at least 6 characters long')</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="confirm_password" class="col-sm-4 col-form-label">@lang('website.Confirm Password')</label>
                                        <div class="col-sm-8">
                                            <input name="confirm_password" type="password" class="form-control" id="confirm_password" placeholder="@lang('website.Confirm Password')">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your Confirm password')</span>
                                        </div>
                                    </div>
                                    <div class="button">
                                        <button type="submit" class="btn btn-dark">@lang('website.Update')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
		</div>		
	</div>
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


