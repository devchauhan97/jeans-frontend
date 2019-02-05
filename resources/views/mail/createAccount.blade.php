<div style="width: 100%; display:block;">
<h2> {{ trans('labels.WelcomeEmailTitle') }}  {{ $app_name }}</h2>
<p>
	<strong>{{ trans('labels.Hi') }} {{ $userData->customers_firstname }} {{ $userData->customers_lastname }}!</strong><br>
	{{ trans('labels.accountCreatedText') }}<br><br>
	<strong>{{ trans('labels.Sincerely') }},</strong><br>
	 {{$app_name}}
</p>
</div>