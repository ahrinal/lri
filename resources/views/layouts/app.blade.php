<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Medilab Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('img/favicon.png')}}" rel="icon">
  <link href="{{asset('img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('css/main.css')}}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Medilab
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Updated: Jun 29 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    .language {
      order: 2;
      margin: 0 15px 0 0;
      padding: 6px 15px;
    }

    /* Custom CSS to change the indicator color */
    .carousel-indicators [data-bs-target] {
      background-color: #dedede;
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }

    .carousel-indicators .active {
      background-color: #1A77CC;
    }

    .news-title {
      font-family: var(--default-font);
      font-size: 11pt;
      font-weight: bold;
      color: #2C4964;
      text-align: justify;
    }

    .news-body {
      font-family: var(--heading-font);
      font-size: 10pt;
      text-align: justify;
    }

    .news-calendar {
      color: #1A77CC;
    }

    .centered-date {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
      /* Adjust as needed */
      color: blue;
    }

    .event-card-container {
      border: none;
    }

    .event-card {
      background-color: #F1F7FC;
      margin-bottom: 15px;
    }

    .event-time {
      background: #14a5e4 none repeat scroll 0 0;
      padding: 63px 25px;
      text-align: center;
    }

    .event-time strong {
      color: #fff;
      display: block;
      /* font-family: "Montserrat", sans-serif; */
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 5px;
      text-transform: uppercase;
    }

    .custom-icon {
      width: 32px;
      /* Adjust the size as needed */
      height: 32px;
      filter: invert(1) sepia(1) saturate(5) hue-rotate(180deg);
      /* Adjust color using filter */
    }

    .centered-content {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
      /* Adjust height if needed */
    }

    .service-item:hover .icon img {
      filter: invert(27%) sepia(92%) saturate(6582%) hue-rotate(184deg) brightness(94%) contrast(96%);
      /* Approximate blue color */
    }
  </style>
</head>
<body class="index-page">
  @section('header')
  @include('layouts.header')
  @show
  <main class="main">
  @yield('content')
  </main>
  
  @section('footer')
  @include('layouts.footer')
  @show

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('vendor/aos/aos.js')}}"></script>
  <script src="{{asset('vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('vendor/purecounter/purecounter_vanilla.js')}}"></script>
  <script src="{{asset('vendor/swiper/swiper-bundle.min.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{asset('js/main.js')}}"></script>

</body>
