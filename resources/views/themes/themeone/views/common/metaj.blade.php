<head>
  @if(empty($web_setting[72]->value))
  <title>Bombayport | {{ $pageTitle }}</title>
  @else
  <title><?=stripslashes($web_setting[18]->value)?></title>
  @endif
  <meta name="DC.title"  content="<?=stripslashes($web_setting[73]->value)?>"/>
  <meta name="description" content="<?=stripslashes($web_setting[75]->value)?>"/>
  <meta name="robots" content="noindex,nofollow">
  <meta name="keywords" content="<?=stripslashes($web_setting[74]->value)?>"/>
  <!-- <meta name="google-signin-client_id" content="31214299992-7pijldgsl0h1f75s8tbq7subrijq36jo.apps.googleusercontent.com"> -->
  
  <link href="{!! asset('css/jquery-ui.min.css') !!} " media="all" rel="stylesheet" type="text/css"/>
  <link rel="shortcut icon" href="{{getFtpImage($web_setting[15]->value)}}" />
  <link rel="stylesheet" type="text/css" href="{!! asset('css/infinite-slider.css') !!}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!------- paypal ---------->
 <!--  <script src="https://www.paypalobjects.com/api/checkout.js"></script> -->
  <!---- onesignal ------>
  @if($web_setting[54]->value == 'onesignal')
    <!--<link href="{!! asset('onesignal/manifest.json') !!} " media="all" rel="manifest"/>-->
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
      var OneSignal = window.OneSignal || [];
      OneSignal.push(function() {
  	  //push here
      });
  	  	
    	//onesignal 
    	OneSignal.push(["init", {
    	  appId: "{{$web_setting[55]->value}}",
    	 // safari_web_id: oneSignalSafariWebId,
    	  persistNotification: false,
    	  notificationClickHandlerMatch: 'origin',
    	  autoRegister: false,	
    	  notifyButton: {
    	   enable: false 
    	  }
    	 }]);  
    	  
    </script>
  @endif
     
  @if(!empty($web_setting[76]->value))
	<?=stripslashes($web_setting[76]->value)?>
  @endif

</head>

