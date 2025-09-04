@extends('layouts.app')
@section('content')
<!--======= HOME MAIN SLIDER =========-->
<div class="home-slider section">
  <!-- Slider Loader -->
  <div id="loader" class="hom-slie">
    <div class="tp-loader spinner0"> <span class="dot1"></span> <span class="dot2"></span> <span class="bounce1"></span> <span class="bounce2"></span> <span class="bounce3"></span> </div>
  </div>

  <!-- SLIDE Start -->
  <div class="tp-banner-container">
    <div class="tp-banner">
      <ul>
        @foreach($slider as $slider)
        <!-- SLIDE  -->
        <li data-transition="random" data-title="intro" data-slotamount="7" data-masterspeed="300" data-saveperformance="off">
          <!-- MAIN IMAGE -->
          <img src="{{asset('storage/'.$slider->image)}}" alt="slider" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat">
          <!-- LAYERS -->

          <!-- LAYER NR. 1 -->
          <div class="tp-caption sfb font-raleway text-left tp-resizeme" data-x="['right','right','left','center']" data-hoffset="['350','300','100','15']" data-y="['center','center','center','center']" data-voffset="['-10','-10','20','20']" data-speed="500" data-start="1000" data-fontsize="['16','16','16','14']" data-fontweight="['700','700','700','700']" data-lineheight="['26','26','26','26']" data-width="['670','670','470','350']" data-height="none" data-easing="Power3.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.0" data-endelementdelay="0.0" data-endspeed="300" data-whitespace="normal" data-responsive_offset="on" style="z-index: 6; color:#fff;">
            {!!$slider->description!!} <br><br><br><br>
          </div>
          <!-- LAYER NR. 3 -->
          

          <!-- LAYER NR. 4 -->
          <div class="tp-caption letter-space-4 sfl tp-resizeme" data-x="['left','left','left','center']" data-hoffset="['-10','0','0','0']" data-y="['center','center','top','top']" data-voffset="['0','0','0','0']" data-speed="500" data-start="800" data-easing="Power3.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="300" data-responsive_offset="on" style="z-index: 1; padding-left: 30px;"> <img src="{{asset('storage/'.$slider->sub_image)}}" alt="" height="100px"> </div>
        </li>
        @endforeach

      </ul>
    </div>
  </div>
</div>

<!-- Content -->
<div id="content">
  <!-- blog -->
  <section class="blog light-gray-bg padding-top-40 padding-bottom-70">
    <div class="container">

      <!-- Heading -->
      <div class="heading text-center margin-bottom-60">
        <h4>{{ __('language.news_title') }}</h4>
        <hr>
      </div>

      <!-- Row -->
      <div class="row">
        <!-- BLOG -->
        @if(!empty($news))
        @foreach($news as $hasil)
        <div class="col-md-4 col-sm-6 col-xs-6">
          <article>
            <img src="{{asset('storage/'.getCroppedFile($hasil->image))}}" class="img-responsive" alt="">
            <div class="post-info">
              <div class="media-left">
                <div class="date">
                  @php
                  $dateTime = new DateTime($hasil->created_at);
                  $formattedDate = $dateTime->format('d M Y');
                  @endphp
                  {{strtoupper($formattedDate)}}
                </div>
              </div>
              <div class="media-body">
                <h4 class="tittle-post">
                  <a href="{{url('news/detail/'.$hasil->id)}}">
                    @if(session('language') === 'id')
                    {{$hasil->judul}}
                    @else
                    {{$hasil->title}}
                    @endif
                  </a>
                </h4>
                <hr class="main">
              </div>
              <div style="margin-top:-10px;">
                @if(session('language') === 'id')
                {!!substr($hasil->deskripsi, 0, 200)!!}
                {{(strlen($hasil->deskripsi)==200)?'...':''}}
                @else
                {!!substr($hasil->description, 0, 200)!!}
                {{(strlen($hasil->deskripsi)==200)?'...':''}}
                @endif
              </div>
              <a href="{{url('news/detail/'.$hasil->id)}}" class="read-more">read more </a>
            </div>
          </article>
        </div>
        @endforeach
        @endif
      </div>
    </div>
  </section>
  <!-- Welcome to Trudel -->
  <section class="welcome padding-top-20 padding-bottom-70">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <!-- Heading -->
          <div class="heading">
            <h4>latest publications</h4>
            <hr>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12">
          @foreach($publications as $publication)
          <div class="news-list" style="border-bottom: solid 1px #dedede; padding-top:20px;">
            <div class="publication-title" style="font-size:large"><a href="{{$publication->link}}" target="_blank">{{$publication->title}}</a></div>
            <div class="news-title">{{$publication->authors}}</div>
            <div class="news-content">{{$publication->publication}}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  
</div>

@endsection