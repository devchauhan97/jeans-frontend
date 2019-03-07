<div class="owl-carousel owl-six owl-theme ">
    @foreach($result['blogs'] as $key=>$list) 
    <div class="item">
        <div class="blog-box box-border bottom-box-shadow">
            <img  src="{{getFtpImage($list->image)}}" alt="{{$list->title}}">
            <div class="blog-action">
                <div class="clear"></div>
                <div class="action-title">
                    <p>
                            {{$list->title}}
                    </p>
                    
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="blog-text">
            {{$list->sort_description}}
            <a href="{{ URL::to('/blog/'.$list->blogs_id)}}" class="btn btn-primary btn-secondary">Read More</a>
        </div>
    </div>
    @endforeach
</div>
 