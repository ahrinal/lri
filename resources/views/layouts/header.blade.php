<header id="header" class="header sticky-top">

    <div class="branding d-flex align-items-center">

        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{asset('img/logo_lri.png')}}" alt="Logo" class="logo-img">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li> <a href="{{url('/')}}" class="{{Request::is('/')?'active':''}}">{{ __('language.home') }}</a> </li>
                    <li> <a href="{{url('/profile')}}" class="{{Request::is('profile*')?'active':''}}">{{ __('language.about') }}</a> </li>
                    <li> <a href="{{url('/news')}}" class="{{Request::is('news*')?'active':''}}">{{ __('language.news') }}</a> </li>
                    <li> <a href="{{url('/publication')}}" class="{{Request::is('publication*')?'active':''}}">{{ __('language.publication') }}</a> </li>
                    <li class="dropdown"><a href="#"><span>{{ __('language.lri') }}</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            @if(session('language') === 'id')
                            @foreach($lri as $lri_type)
                            <li> <a href="/lri/{{$lri_type->id}}">{{str_replace("Lembaga Riset Internasional","",$lri_type->nama)}}</a> </li>
                            @endforeach
                            @else
                            @foreach($lri as $lri_type)
                            <li> <a href="/lri/{{$lri_type->id}}">{{str_replace("International Research Institute for","",$lri_type->name)}}</a> </li>
                            @endforeach
                            @endif
                        </ul>
                    </li>
                    <li> <a href="{{url('/gallery')}}" class="{{Request::is('gallery*')?'active':''}}">{{ __('language.gallery') }}</a> </li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <!-- <a class="cta-btn d-none d-sm-block" href="#appointment">Make an Appointment</a> -->
            <div class="language">
                <a href="" id="en">
                    <img class="en" src="{{asset('img/en.png')}}" width="25px" alt="">
                </a>
                &nbsp;
                <a href="" id="id">
                    <img class="id" src="{{asset('img/id.png')}}" width="25px" alt="">
                </a>
            </div>

        </div>

    </div>

</header>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
    $(document).ready(function() {
        @if(session('language') == 'id')
        $('.id').css({
            'filter': 'none'
        });
        $('.en').css({
            'filter': 'grayscale(100%)'
        });
        @else
        $('.id').css({
            'filter': 'grayscale(100%)'
        });
        $('.en').css({
            'filter': 'none'
        });
        @endif
        $('#en').click(function() {
            $('.id').css({
                'filter': 'grayscale(100%)'
            });
            $('.en').css({
                'filter': 'none'
            });
            $.post("{{ route('language.switch') }}", {
                    language: 'en',
                    _token: "{{ csrf_token() }}"
                })
                .done(function(data) {
                    // Handle success, if needed
                    location.reload(); // Reload the page after language switch
                })
                .fail(function(error) {
                    // Handle error, if needed
                    console.error(error);
                });

        });
        $('#id').click(function() {
            $('.id').css({
                'filter': 'none'
            });
            $('.en').css({
                'filter': 'grayscale(100%)'
            });
            $.post("{{ route('language.switch') }}", {
                    language: 'id',
                    _token: "{{ csrf_token() }}"
                })
                .done(function(data) {
                    // Handle success, if needed
                    location.reload(); // Reload the page after language switch
                })
                .fail(function(error) {
                    // Handle error, if needed
                    console.error(error);
                });

        });
    });
</script>