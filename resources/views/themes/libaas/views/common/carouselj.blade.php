<section class="page-header">
	<div class="hero-area">
		<div class="owl-carousel owl-one owl-theme">
			@foreach($result['slides'] as $key=>$slides_data)
			<div class="item" style="background: url({{getFtpImage($slides_data->image)}})">

			</div>
			@endforeach
		</div>
	</div>
</section>

 