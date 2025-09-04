@extends('layouts.app')
@section('content')
<section id="departments" class="departments section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Berita</h2>
        <p>
            @if(session('language') === 'id')
            {{$news->institution->nama}}
            @else
            {{$news->institution->name}}
            @endif
        </p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
            <div class="col-lg-3">
                <ul class="nav nav-tabs flex-column">
                    @foreach($latest_news as $berita_akhir)
                    <li class="nav-item">
                        <a class="nav-link @if($berita_akhir->id == $news->id) active @endif" href="{{url('news/detail/'.$berita_akhir->id)}}">
                            @if(session('language') === 'id')
                            {{$berita_akhir->judul}}
                            @else
                            {{$berita_akhir->title}}
                            @endif
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-9 mt-4 mt-lg-0">
                <div class="tab-content">
                    <div class="tab-pane active show" id="departments-tab-1">
                        <div class="row">
                            <div class="col-lg-12 details order-2 order-lg-1">
                                <h3>@if(session('language') === 'id')
                                    {{$news->judul}}
                                    @else
                                    {{$news->title}}
                                    @endif
                                </h3>
                                <p><img class="" src="{{asset('storage/'.$news->image)}}" alt=""></p>
                                <p>
                                    @if(session('language') === 'id')
                                    {!!$news->deskripsi!!}
                                    @else
                                    {!!$news->description!!}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</section><!-- /Departments Section -->
@endsection