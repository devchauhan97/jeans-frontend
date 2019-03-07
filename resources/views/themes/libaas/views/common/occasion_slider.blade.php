 @foreach($result['occasion_slides'] as $key=>$cat) 
    <div class="item">
        <div class="cate-box">
            <img src="{{getFtpImage($cat->categories_image)}}">
            <div class="cate-box-in">
                <p>New</p>
                <h3>{{$cat->categories_name}}</h3>
                <a href="{{ URL::to('/shop?category='.$cat->categories_slug)}}"><i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
@endforeach
 
 