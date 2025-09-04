@extends('layouts.app')
@section('content')
<!-- Gallery Section -->
<section id="gallery" class="gallery section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Gallery</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
    </div><!-- End Section Title -->

    <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">
            @foreach($models as $model)
            <div class="col-lg-3 col-md-4">
                <div class="gallery-item">
                    <a href="{{asset('storage/'.$model->image)}}" class="glightbox" data-gallery="images-gallery">
                        <img src="{{asset('storage/'.getCroppedFile($model->image))}}" alt="" class="img-fluid">
                    </a>
                </div>
            </div><!-- End Gallery Item -->
            @endforeach
        </div>
        {!! $models->links('pagination::bootstrap-4') !!}
    </div>

</section><!-- /Gallery Section -->


@endsection