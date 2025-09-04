@extends('layouts.app')
@section('content')
<style>
    .judul{
        text-align: center;
        font-size: 2rem;
        background-color: #f2f2f2;
    }
</style>
<div class="judul">
</div>
<section id="departments" class="departments section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>{{ __('language.profile_title') }}</h2>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
            <div class="col-lg-12">
                <!-- Heading -->
                <div class="heading">
                    <h4>{{ __('language.whatis_lri') }} ?</h4>
                    <hr>
                </div>
                <p>
                    {!! $profile->description !!}
                </p>
            </div>
        </div>

    </div>

</section><!-- /Departments Section -->
@endsection