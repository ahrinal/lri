@extends('layouts.app')
@section('content')
<!-- SUB BANNER -->
<section class="sub-bnr">
    <div class="position-center-center">
        <div class="container">
            <h4>{{ __('language.news') }}</h4>
            <img src="images/bg-sub-bnr.png" alt="">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="#">{{ __('language.home') }}</a></li>
                <li class="active">{{ strtoupper(__('language.news')) }}</li>
            </ol>
        </div>
    </div>
</section>
<!-- Content -->
<div id="content">
    <div class="blog blog-page padding-top-50 padding-bottom-100 section">
        <div class="container">

            <!-- Row -->
            <div class="row">

                <!-- BLOG -->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="news-post">
                        <ul class="berita-post">
                            @foreach($models as $model)
                            <li class="media">
                                <div class="media-left"> <a href="#"> <img class="media-object" src="{{asset('storage/'.getCroppedFile($model->image))}}" alt=""></a> </div>
                                <div class="media-body">
                                    <p>
                                        @if(session('language') === 'id')
                                        {{$model->institution->nama}}
                                        @else
                                        {{$model->institution->name}}
                                        @endif
                                    </p>
                                    <a class="media-heading" href="{{url('news/detail/'.$model->id)}}">
                                        @if(session('language') === 'id')
                                        {{$model->judul}}
                                        @else
                                        {{$model->title}}
                                        @endif
                                    </a>
                                    <span>
                                        @php
                                        $dateTime = new DateTime($model->created_at);
                                        $formattedDate = $dateTime->format('d M Y');
                                        @endphp
                                        {{strtoupper($formattedDate)}}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        {!! $models->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection