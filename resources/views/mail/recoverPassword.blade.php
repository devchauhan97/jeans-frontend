<div style="width: 100%; display:block;">
<h2>{{ trans('labels.EcommercePasswordRecovery') }}</h2>
<p>
	<strong>{{ trans('labels.Hi') }} {{ $user->customers_firstname }} {{ $user->customers_lastname }}!</strong><br>
	{{ trans('labels.recoverPasswordEmailText') }}<br>
	<a href="{{$url}}"> Click here to Reset.</a>
	<br><br>
	<strong>{{ trans('labels.Sincerely') }},</strong><br>
	{{ trans('labels.regardsForThanks') }}
</p>
</div>