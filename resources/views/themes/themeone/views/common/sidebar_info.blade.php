<div class="sidebar">
    <div class="widget block-categories">
        <div class="block-title">
            <h2>Info Pages</h2>
        </div>
        <div class="block-content">
            <ul class="list-categories">
            
                @if(count($result['commonContent']['pages']))
                @foreach($result['commonContent']['pages'] as $page)
                    <li> <a href="{{ URL::to('/page?name='.$page->slug)}}">{{$page->name}}</a> </li>
                @endforeach
                @endif
                
            </ul>
        </div>
    </div>
    <div class="widget block-images">
    	<ul class="list-images en">
        	<li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_1_en.jpg'}}" alt="image"></a></li>
            <li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_2_en.jpg'}}" alt="image"></a></li>
            <li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_3_en.jpg'}}" alt="image"></a></li>
        </ul>
        
        <ul class="list-images ar">
        	<li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_1_ar.jpg'}}" alt="image"></a></li>
            <li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_2_ar.jpg'}}" alt="image"></a></li>
            <li><a href="{{ URL::to('/shop')}}"><img src="{{asset('').'images/banner_3_ar.jpg'}}" alt="image"></a></li>
        </ul>
    </div>
</div>