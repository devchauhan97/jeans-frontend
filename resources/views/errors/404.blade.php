@extends('layouts')
 
@section('content')
 <section class="content main-container" id="site-content">
        <div class="ptb-40">
            <div class="container">
                <h1>
                    Oops!</h1>
                <h2>
                    404 Not Found</h2>
                <div class="error-details">

                    @if($errors->any())
                      <h4>{{$errors->first()}}</h4>
                    @else
                    Sorry, an error has occured, Requested page not found!
                    @endif
                </div>
                 
            </div>
        </div>
    </div>
</section>
@endsection
@section('customjs')

@endsection