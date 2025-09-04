@extends('layouts.app')
@section('content')
<!-- Carousel -->
<div id="demo" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">

  <!-- Indicators/dots -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
  </div>

  <!-- The slideshow/carousel -->
  <div class="carousel-inner">
    @php $i = 1; @endphp
    @foreach($slider as $hasil)
    <div class="carousel-item @if($i==1) active @endif">
      <img src="{{asset('storage/'.$hasil->image)}}" alt="Los Angeles" class="d-block" style="width:100%">
    </div>
    @php $i++; @endphp
    @endforeach
  </div>

  <!-- Left and right controls/icons -->
  <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>


<!-- Stats Section -->
<section id="stats" class="stats section light-background">
  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>LATEST NEWS</h2>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row gy-4">
      @if(!empty($news))
      @foreach($news as $hasil)
      <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center">
        <div class="card">
          <img src="{{asset('storage/'.getCroppedFile($hasil->image))}}" class="img-responsive" alt="">
          <div class="card-body">
            <p class="news-title">
              @if(session('language') === 'id')
              {{$hasil->judul}}
              @else
              {{$hasil->title}}
              @endif
            </p>
            <p class="news-body"><span class="fa fa-calendar-alt news-calendar"></span>
              @php
              $dateTime = new DateTime($hasil->created_at);
              $formattedDate = $dateTime->format('d M Y');
              @endphp
              {{strtoupper($formattedDate)}}
            </p>
            <p class="news-body">
              @if(session('language') === 'id')
              {!!substr($hasil->deskripsi, 0, 200)!!}
              {{(strlen($hasil->deskripsi)==200)?'...':''}}
              @else
              {!!substr($hasil->description, 0, 200)!!}
              {{(strlen($hasil->deskripsi)==200)?'...':''}}
              @endif
            </p>
            <a href="{{url('news/detail/'.$hasil->id)}}" class="news-body">read more</a>
          </div>
        </div>
      </div>
      @endforeach
      @endif
    </div>

  </div>

</section><!-- /Stats Section -->

<!-- Services Section -->
<section id="services" class="services section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>NEXT EVENT</h2>
  </div><!-- End Section Title -->

  <div class="container">
    <div class="card mb-12 event-card-container">
      <div class="row g-0 event-card">
        <div class="col-md-2 event-time">
          <strong>Jumat, 6 Juni 2024</strong>
        </div>
        <div class="col-md-10">
          <div class="card-body">
            <p class="news-title">IPB University Graduate School Conducts Monev of BPI Scholarship Recipient
              Students</p>
            <p class="news-body">The Graduate School (SPs) of IPB University and the Higher Education Financing
              Center (BPPT), Ministry of Education, Culture, Research and Technology (Kemendikbud Riste</p>
          </div>
        </div>
      </div>
      <div class="row g-0 event-card">
        <div class="col-md-2 event-time">
          <strong>Jumat, 6 Juni 2024</strong>
        </div>
        <div class="col-md-10">
          <div class="card-body">
            <p class="news-title">IPB University Graduate School Conducts Monev of BPI Scholarship Recipient
              Students</p>
            <p class="news-body">IPB University melalui International Research Institute for Environment and Climate
              Change (IRIECC) atau Lembaga Riset Internasional – Lingkungan dan Perubahan Iklim ( LRI – LPI)
              bersama.</p>
          </div>
        </div>
      </div>
      <div class="row g-0 event-card">
        <div class="col-md-2 event-time">
          <strong>Jumat, 6 Juni 2024</strong>
        </div>
        <div class="col-md-10">
          <div class="card-body">
            <p class="news-title">IPB University Graduate School Conducts Monev of BPI Scholarship Recipient
              Students</p>
            <p class="news-body">IPB University melalui International Research Institute for Environment and Climate
              Change (IRIECC) atau Lembaga Riset Internasional – Lingkungan dan Perubahan Iklim ( LRI – LPI)
              bersama.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</section><!-- /Services Section -->

<!-- Services Section -->
<section id="services" class="services section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>INTERNATIONAL RESEARCH INSTITUTE</h2>

  </div><!-- End Section Title -->

  <div class="container">

    <div class="row">

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="service-item  position-relative">
          <div class="icon">
            <img src="https://www.svgrepo.com/show/533478/building-user.svg" alt="Custom Icon" class="custom-icon">
          </div>
          <a href="#" class="stretched-link">
            <h3>International Research Institute for Social, Economics and Regional Studies</h3>
          </a>

        </div>
      </div><!-- End Service Item -->

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="service-item position-relative">
          <div class="icon">
            <img src="https://www.svgrepo.com/show/16219/healthy-nutrition.svg" alt="Custom Icon" class="custom-icon">
          </div>
          <a href="#" class="stretched-link">
            <h3>International Research Institute for Food, Nutrition and Health</h3>
          </a>
        </div>
      </div><!-- End Service Item -->

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="service-item position-relative">
          <div class="icon">
            <img src="https://www.svgrepo.com/show/258470/global-warming.svg" alt="Custom Icon" class="custom-icon">
          </div>
          <a href="#" class="stretched-link">
            <h3>International Research Institute for Environment and Climate Change</h3>
          </a>
        </div>
      </div><!-- End Service Item -->

    </div>
    <br>
    <div class="row">
      <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="400"></div>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
        <div class="service-item position-relative">
          <div class="icon">
            <img src="https://www.svgrepo.com/show/321245/processor.svg" alt="Custom Icon" class="custom-icon">
          </div>
          <a href="#" class="stretched-link">
            <h3>International Research Institute for Advanced Technology</h3>
          </a>
          <a href="#" class="stretched-link"></a>
        </div>
      </div><!-- End Service Item -->

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
        <div class="service-item position-relative">
          <div class="icon">
            <img src="https://www.svgrepo.com/show/78081/oceanic-cargo-ship-global-distribution.svg" alt="Custom Icon" class="custom-icon">
          </div>
          <a href="#" class="stretched-link">
            <h3>International Research Institute for Maritime, Ocean and Fisheries (i-MAR)</h3>
          </a>
          <a href="#" class="stretched-link"></a>
        </div>
      </div><!-- End Service Item -->
      <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="400"></div>
    </div>

  </div>

</section><!-- /Services Section -->

<!-- Stats Section -->
<section id="stats" class="stats section light-background">
  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>OUR PARTNERSHIPS</h2>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row gy-4">
      <div class="col-lg-8 col-md-8 d-flex flex-column ">
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css">

        <script src="https://unpkg.com/leaflet@1.3.3/dist/leaflet.js"></script>

        <script src="https://elfalem.github.io/Leaflet.curve/src/leaflet.curve.js"></script>

        <style>
          body {
            padding: 0px;
            margin: 0px;
          }

          #mapid {
            height: 450px;
          }
        </style>
        <div id="mapid"></div>
        <script>
          // Inisialisasi peta dengan pusat di antara Pulau Jawa dan New Delhi
          var map = L.map('mapid').setView([0, 0], 2);

          // Tambahkan layer OpenStreetMap
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(map);

          // Koordinat Pulau Jawa, Indonesia
          var latlng1 = [-7.614529, 110.712246]; // Koordinat di sekitar Yogyakarta, Jawa Tengah

          // Koordinat New Delhi, India
          var latlng2 = [51.98650935159234, 5.668061812479006];// Koordinat New Delhi

          // Menghitung offset dan midpoint untuk kurva
          var offsetX = latlng2[1] - latlng1[1],
            offsetY = latlng2[0] - latlng1[0];

          var r = Math.sqrt(Math.pow(offsetX, 2) + Math.pow(offsetY, 2)),
            theta = Math.atan2(offsetY, offsetX);

          var thetaOffset = (Math.PI / 10); // Sudut offset untuk lengkungan

          var r2 = (r / 2) / (Math.cos(thetaOffset)),
            theta2 = theta + thetaOffset;

          var midpointX = (r2 * Math.cos(theta2)) + latlng1[1],
            midpointY = (r2 * Math.sin(theta2)) + latlng1[0];

          var midpointLatLng = [midpointY, midpointX];

          // Array untuk menyimpan koordinat
          var latlngs = [];
          latlngs.push(latlng1, midpointLatLng, latlng2);

          // Pengaturan tampilan path
          var pathOptions = {
            color: 'blue', // Warna kurva
            weight: 2 // Ketebalan kurva
          };

          // Membuat path kurva antara Pulau Jawa dan New Delhi
          var curvedPath = L.curve(
            [
              'M', latlng1,
              'Q', midpointLatLng,
              latlng2
            ], pathOptions).addTo(map);

          // Custom icon untuk Pulau Jawa
          var javaIcon = L.icon({
            iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/Bogor_Agricultural_University_%28IPB%29_symbol.svg/2048px-Bogor_Agricultural_University_%28IPB%29_symbol.svg.png', // Ganti dengan URL gambar icon Pulau Jawa
            iconSize: [30, 30], // Ukuran icon
            iconAnchor: [20, 30], // Titik anchor icon (bagian bawah tengah)
            popupAnchor: [0, -30] // Titik anchor popup relatif terhadap icon
          });

          // Custom icon untuk New Delhi
          var delhiIcon = L.icon({
            iconUrl: 'https://landportal.org/sites/landportal.org/files/WUR-logo4.jpg', // Ganti dengan URL gambar icon New Delhi
            iconSize: [30, 30], // Ukuran icon
            iconAnchor: [20, 30], // Titik anchor icon (bagian bawah tengah)
            popupAnchor: [0, -50] // Titik anchor popup relatif terhadap icon
          });

          // Tambahkan marker dengan custom icon untuk Pulau Jawa
          L.marker(latlng1, {
            icon: javaIcon
          }).addTo(map).bindPopup("<b>Pulau Jawa</b><br>Indonesia");

          // Tambahkan marker dengan custom icon untuk New Delhi
          L.marker(latlng2, {
            icon: delhiIcon
          }).addTo(map).bindPopup("<b>New Delhi</b><br>India");
        </script>

      </div>
    </div>

  </div>

</section><!-- /Stats Section -->
<!-- Content -->
<div id="content">
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