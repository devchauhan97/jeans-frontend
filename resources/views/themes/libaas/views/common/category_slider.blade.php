<div class="owl-carousel owl-two owl-theme">
    <div class="item">
        <div class="cate-box box-border bottom-box-shadow">
            <img src="{{getFtpImage($result['new_arrival']->products_image)}}">
            <h3>New Arrivals</h3>
            <a href="{{Url::to('/shop')}}"><i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
   @foreach($result['cat_slides'] as $key=>$cat) 
    <div class="item">
        <div class="cate-box box-border bottom-box-shadow">
            <img src="{{getFtpImage($cat->categories_image)}}">
            <h3>{{$cat->categories_name}}</h3>
            <a href="{{ URL::to('/shop?category='.$cat->categories_slug)}}"><i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    @endforeach        
</div>


 