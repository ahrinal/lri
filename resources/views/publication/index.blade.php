@extends('layouts.app')
@section('content')
<!-- SUB BANNER -->
<section class="sub-bnr">
    <div class="position-center-center">
        <div class="container">
            <h4>{{ __('language.publication') }}</h4>
            <img src="images/bg-sub-bnr.png" alt="">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="#">{{ __('language.home') }}</a></li>
                <li class="active">{{ strtoupper(__('language.publication')) }}</li>
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
                <div class="col-md-12 col-sm-12">
                    @foreach($models as $publication)
                    <div class="news-list" style="border-bottom: solid 1px #dedede; padding-top:20px;">
                        <div class="publication-title" style="font-size:large"><a href="{{$publication->link}}" target="_blank">{{$publication->title}}</a></div>
                        <div class="news-title">{{$publication->authors}}</div>
                        <div class="news-content">{{$publication->publication}}</div>
                    </div>
                    @endforeach
                    {!! $models->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection