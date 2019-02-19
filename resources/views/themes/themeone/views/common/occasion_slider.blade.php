<section class="featured-product padding-50 light-grey">
    <div class="wrapper">
       <h2 class="text-center">Occasion</h2>
        <div class="row">
        @foreach($result['occasion_slides'] as $key=>$cat) 
       
            <div class="col-md-3 col-sm-6 col-3">
                
                <div class="bg-grey text-center">
                <img src="{{getFtpImage($cat->categories_image)}}" alt="{{$cat->categories_name}}" width="300"> 
                           
                </div>
                <a href="{{ URL::to('/shop?category='.$cat->categories_slug)}}" class="featured-content text-center">
                    <span class="product-title">{{$cat->categories_name}}</span>
                     
                </a>
            </div>
        
        @endforeach

    </div>
    
</section>
 