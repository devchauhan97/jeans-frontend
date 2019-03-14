@extends('layouts')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> <!-- Popper plugin for Bootstrap -->
<section class="page-header" style="height: 120px;">
</section>
<!-- Site Content -->
<section class="content main-container" id="site-content">
    <div class="ptb-40">
        
    <div class="clear"></div>
    <div class="blog-area border-0">
        <div class="container text-center ">
            <h3 class="mb-40 mb-20-m">Blog Posts</h3>
        </div>
        <div class="container">
            <div class="row">
                @foreach($result['blogs'] as $blogs)
                <div class="col-sm-3">
                    <div class="mb-30">
                        <div class="blog-box box-border bottom-box-shadow">
                            <img class="img-fluid w-100" src="{{ getFtpImage($blogs->image) }}" alt="{{ $blogs->title}}">
                            <div class="blog-action">
                                <div class="clear"></div>
                                <div class="action-title">
                                    <p>
                                        {{ $blogs->title }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="blog-text">
                            <p>{{ $blogs->sort_description }}</p>
                            <a href="{{ URL::to('blog/'.$blogs->blogs_id) }}" class="btn btn-primary btn-secondary">Read More</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection




