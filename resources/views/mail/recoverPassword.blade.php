<div style="width: 100%; display:block;">
<h2>{{$app_name}} {{ trans('labels.PasswordRecovery') }} </h2>
<p>
	<strong>{{ trans('labels.Hi') }} {{ $user->customers_firstname }} {{ $user->customers_lastname }}!</strong><br>
	{{ trans('labels.recoverPasswordEmailText') }}<br>
	<a href="{{$url}}"> Click here to Reset.</a>
	<br><br>
	<strong>{{ trans('labels.Sincerely') }},</strong><br>
	{{ $app_name }}
</p>
</div>