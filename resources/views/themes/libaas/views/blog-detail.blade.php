@extends('layouts')
 
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script> <!-- Popper plugin for Bootstrap -->
 <!-- Site Page Header -->
  <section class="page-header">
    <div class="showcase-area">
      <img src="{{getFtpImage($result['blogs_detail']->image)}}">
    </div>
  </section>
  <div class="clear"></div>
  <!-- Site Content -->
  <section class="content main-container" id="site-content">
    <div class="ptb-40">
      <div class="blog-detail">
        <div class="container">
          <div class="row">
            <div class="offset-md-1 col-md-10 col-sm-12">
              <div class="blog-header">
                <img src="{!! asset('images/blog-user.png') !!}">
                <ul>
                  <li><a ><strong>{{$result['blogs_detail']->first_name}} {{$result['blogs_detail']->last_name}}</strong></a></li>
                  <li><a > <strong>|</strong> </a></li>
                  <li><a >Marketing Manager</a></li>
                </ul>
              </div>
              <div class="blog-body">
                <div class="blog-date">
                  <div class="date-in">
                    <strong>{{date('d',strtotime($result['blogs_detail']->created_at))}}</strong>
                    <span>{{date('M',strtotime($result['blogs_detail']->created_at))}}, {{date('y',strtotime($result['blogs_detail']->created_at))}}</span>
                  </div>
                  <div class="detail-share">
                    <ul>
                      <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                      <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                      <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                      <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      <li><a href="#"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                    </ul>
                  </div>
                </div>
                <div class="blog-body-in">
                  <h3>{{$result['blogs_detail']->title}}</h3>
                   <?=stripslashes($result['blogs_detail']->description)?>
                       
                  <!-- <div class="spacer-30"></div>
                  <img src="{!! asset('images/blog-img1.png') !!}" class="width-100 img-fluid">
                  <div class="spacer-30"></div>
                  <p>
                     {{$result['blogs_detail']->description}}
                  </p>
                  <div class="spacer-30"></div>
                  <img class="ml-70 border-8-white" src="{!! asset('images/blog-img2.png') !!}">
                  <div class="spacer-30"></div>
                  <p>
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. 
                  </p> -->
                  <div class="spacer-30"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="blog-area">
        <div class="container">
          <div class="row">
            <div class="offset-md-1 col-md-10">
              <div class="row">
                @foreach($result['blogs'] as $key=>$list) 
                <div class="col-md-4 col-sm-6 col-xs-6">
                  <div class="mtb-40">
                    <div class="blog-box box-border bottom-box-shadow">
                      <img class="img-fluid w-100" src="{{getFtpImage($list->image)}}" alt="{{$list->title}}">
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
                      <p>{{$list->sort_description}}</p>
                      <a href="{{ URL::to('/blog/'.$list->blogs_id)}}" class="btn btn-primary btn-secondary">Read More</a>
                    </div>
                  </div>
                </div>
                 @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

     <!--  <div class="comment-area mtb-40">
        <div class="container">
          <div class="row">
            <div class="offset-md-1 col-md-10">
              <h3>Comment 2</h3>
              <div class="comment-box">
                <div class="row">
                  <div class="col-md-2 col-sm-3 col-xs-3">
                    <img class="width-100 img-fluid" src="{!! asset('images/blog-img3.png') !!}">
                  </div>
                  <div class="col-md-10 col-sm-9 col-xs-9">
                    <h4>Cynthia Fowler</h4>
                    <span>February  28,  2019</span>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi  aliquip ex ea commodo consequat. </p>
                    <a class="btn btn-primary btn-dark" href="#">Reply <i class="fa fa-reply" aria-hidden="true"></i></a>
                    <div class="comment-box">
                      <div class="row">
                        <div class="col-md-2 col-sm-3 col-xs-3">
                          <img class="width-100 img-fluid" src="{!! asset('images/blog-img3.png') !!}">
                        </div>
                        <div class="col-md-10 col-sm-9 col-xs-9">
                          <h4>Cynthia Fowler</h4>
                          <span>February  28,  2019</span>
                          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi  aliquip ex ea commodo consequat. </p>
                          <a class="btn btn-primary btn-dark" href="#">Reply <i class="fa fa-reply" aria-hidden="true"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="comment-form pt-40">
        <div class="container">
          <div class="row">
            <div class="offset-md-1 col-md-10">
              <div class="text-center">
                  <h3>Leave a Comment</h3>
              </div>
              <div class="spacer-30"></div>
              <div class="row">
                <div class="col-md-6">
                  <input type="text" placeholder="Name" >
                  <input type="email" placeholder="Mail">
                  <input type="text" placeholder="Website">
                  <button class="btn btn-primary">Submit</button>
                </div>
                <div class="col-md-6">
                  <textarea placeholder="Your Massege"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->

    </div>
    <div class="clear"></div>

  </section>
@endsection   


