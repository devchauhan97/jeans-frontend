<head>
  <!-- <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-55ZQ3ZP');</script> -->
  @if(empty($web_setting[72]->value))
  <title>Bombayport | {{ $pageTitle }}</title>
  @else
  <title><?=stripslashes($web_setting[18]->value)?></title>
  @endif
  <meta name="DC.title"  content="<?=stripslashes($web_setting[73]->value)?>"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?=stripslashes($web_setting[75]->value)?>"/>
  <meta name="robots" content="noindex,nofollow">
  <meta name="keywords" content="<?=stripslashes($web_setting[74]->value)?>"/>
  <meta name="google-signin-client_id" content="31214299992-7pijldgsl0h1f75s8tbq7subrijq36jo.apps.googleusercontent.com">
  <!-- <link rel="stylesheet" type="text/css" href="{!! asset('css/infinite-slider.css') !!}"> -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

 <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" /> -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
  <link rel="shortcut icon" href="{{getFtpImage($web_setting[15]->value)}}" />
  <link href="{!! asset('scss/style.css') !!}" rel="stylesheet" type="text/css"> <!-- Template Stylesheet -->
  <link href="https://use.fontawesome.com/releases/v5.0.1/css/all.css" rel="stylesheet"> <!-- Font Awesome v5 -->
  <link rel="stylesheet" href="{!! asset('css/font-awesome.min.css') !!}">
  <link href="{!! asset('vendor/owl-carousel/css/owl.carousel.min.css') !!}" rel="stylesheet" type="text/css"> <!-- Owl Carousel Stylesheet -->
  <link href="{!! asset('vendor/owl-carousel/css/owl.theme.green.min.css') !!}" rel="stylesheet" type="text/css"> <!-- Owl Carousel Theme Stylesheet -->
  <link href="https://fonts.googleapis.com/css?family=Lato:300i,400,400i,700,700i,900" rel="stylesheet">
  <script src="{!! asset('js/jquery-1.12.4.min.js') !!}"></script>

  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.green.min.css" />
  <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}">
 <link href="https://fonts.googleapis.com/css?family=Lato:300i,400,400i,700,700i,900" rel="stylesheet"> -->

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
<style type="text/css">
  #message_content {
    visibility: hidden;
    min-width: 250px;
    margin-left: -125px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 2px;
    padding: 16px;
    position: fixed;
    z-index:9999;
    left: 50%;
    bottom: 30px;
    font-size: 17px;
}

#message_content.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

.loader {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #fff;
  opacity: 0.8;
  z-index: 99999;
}
.help-block{
  color: red;
}
</style>

