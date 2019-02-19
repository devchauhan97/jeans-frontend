<section class="featured-product padding-50 light-grey">
    <div class="wrapper">
        <h2 class="text-center">Blogs Post</h2>
        <div class="row">
        @foreach($result['blogs'] as $key=>$list) 
         
        <div class="col-md-3 col-sm-6 col-3">
            
            <div class="bg-grey text-center">
            <img src="{{getFtpImage($list->image)}}" alt="{{$list->title}}" width="300"> 
            </div>
            <a href="{{ URL::to('/blog/'.$list->blogs_id)}}" class="featured-content text-center">
                <span class="product-title">{{$list->title}}</span>
                 
            </a>
        </div>
           
        @endforeach

    </div>
    
</section>
 