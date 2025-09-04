@extends('layouts.app')
@section('content')
<!-- Content -->
<div id="content">
    <div class="blog blog-page padding-top-50 padding-bottom-100 section">
      <div class="container"> 
        
        <!-- Row -->
        <div class="row"> 
          
          <!-- BLOG -->
          <div class="col-md-9 col-sm-6 col-xs-12">
            <div class="news-post">
              <article> <img class="img-responsive" src="{{asset('storage/'.$news->image)}}" alt="" >
                <div class="post-info">
                  <div class="media-left">
                    <div class="date">
                    @php
                    $dateTime = new DateTime($news->created_at);
                    $formattedDate = $dateTime->format('d M Y');
                    @endphp
                    {{strtoupper($formattedDate)}}
                    </div>
                  </div>
                  <div class="media-body"> 
                    <h4 class="tittle-post">
                    @if(session('language') === 'id')
                    {{$news->judul}}
                    @else
                    {{$news->title}}
                    @endif
                    </h4>
                    <hr class="main">
                    <span>
                    {{ __('language.news_published') }} : 
                      <span class="primary-color"> 
                        @if(session('language') === 'id')
                          {{$news->institution->nama}} 
                        @else
                          {{$news->institution->name}}
                        @endif
                      </span>
                    </span> 
                  </div>
                    <p class="text-justify">
                        @if(session('language') === 'id')
                        {!!$news->deskripsi!!}
                        @else
                        {!!$news->description!!}
                        @endif
                    </p>
                  
                  <!-- Shaer -->
                  <ul class="pull-left icons">
                    <li><i class="icon-heart"></i></li>
                    <li><i class="icon-bubbles"></i></li>
                    <li><i class="icon-share"></i></li>
                  </ul>
                </div>
              </article>
              
            </div>
          </div>
          
          <!-- Side Bar -->
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="side-bar"> 
              
              <!--======= Search =========-->
              <div class="search">
                <input class="form-control" type="search" placeholder="Search">
                <button type="submit"><i class="fa fa-search"></i></button>
              </div>
              <!--======= Recent Posts =========-->
              <h5 class="side-tittle margin-top-50">Latest News </h5>
              <hr class="main">
              <ul class="papu-post">
                @foreach($latest_news as $berita_akhir)
                <li class="media">
                  <div class="media-left"> <a href="#"> <img class="media-object" src="{{asset('storage/'.getCroppedFile($berita_akhir->image))}}" alt=""></a> </div>
                  <div class="media-body"> 
                    <a class="media-heading" href="{{url('news/detail/'.$berita_akhir->id)}}">
                    @if(session('language') === 'id')
                    {{$berita_akhir->judul}}
                    @else
                    {{$berita_akhir->title}}
                    @endif
                    </a> 
                    <span>
                    @php
                    $dateTime = new DateTime($berita_akhir->created_at);
                    $formattedDate = $dateTime->format('d M Y');
                    @endphp
                    {{strtoupper($formattedDate)}}
                    </span> 
                  </div>
                </li>
                @endforeach
              </ul>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection