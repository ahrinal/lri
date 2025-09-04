@extends('layouts.app')
@section('content')
<style>
    .pr-12 {
        padding-right: 12px !important;
    }

    .mb-20 {
        margin-bottom: 20px !important;
    }

    .b-1 {
        border: 1px solid #ebebeb !important;
    }

    .card {
        border: 0;
        border-radius: 0;
        margin-bottom: 30px;
        -webkit-transition: .5s;
        transition: .5s;
    }

    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
    }

    .media {
        padding: 16px 12px;
        -webkit-transition: background-color .2s linear;
        transition: background-color .2s linear;
    }

    .media {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: start;
        align-items: flex-start;
    }

    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.25rem;
    }

    .media .avatar {
        flex-shrink: 0;
    }

    .no-radius {
        border-radius: 0 !important;
    }

    .avatar-xl {
        width: 120px;
        height: 120px;
        line-height: 64px;
        font-size: 1.25rem;
    }

    .avatar {
        position: relative;
        display: inline-block;
        width: 120px;
        height: 120px;
        line-height: 36px;
        text-align: center;
        border-radius: 100%;
        background-color: #f5f6f7;
        color: #8b95a5;
        text-transform: uppercase;
        margin-top: 20px;
    }

    img {
        max-width: 100%;
    }

    img {
        vertical-align: middle;
        border-style: none;
    }

    .mb-2 {
        margin-bottom: .5rem !important;
    }

    .fs-20 {
        font-size: 20px !important;
    }

    .pr-16 {
        padding-right: 16px !important;
    }

    .ls-1 {
        letter-spacing: 1px !important;
    }

    .fw-300 {
        font-weight: 300 !important;
    }

    .fs-16 {
        font-size: 16px !important;
    }

    .media-body>* {
        margin-bottom: 0;
    }

    small,
    time,
    .small {
        font-family: Roboto, sans-serif;
        font-weight: 400;
        font-size: 11px;
        color: #8b95a5;
    }

    .fs-14 {
        font-size: 14px !important;
    }

    .mb-12 {
        margin-bottom: 12px !important;
    }

    .text-fade {
        color: rgba(77, 82, 89, 0.7) !important;
    }

    .card-footer:last-child {
        border-radius: 0 0 calc(.25rem - 1px) calc(.25rem - 1px);
    }

    .card-footer {
        background-color: #fcfdfe;
        border-top: 1px solid rgba(77, 82, 89, 0.07);
        color: #8b95a5;
        padding: 10px 20px;
    }

    .flexbox {
        display: -webkit-box;
        display: flex;
        -webkit-box-pack: justify;
        justify-content: space-between;
    }

    .align-items-center {
        -ms-flex-align: center !important;
        align-items: center !important;
    }

    .card-footer {
        padding: .75rem 1.25rem;
        background-color: rgba(0, 0, 0, .03);
        border-top: 1px solid rgba(0, 0, 0, .125);
    }


    .card-footer {
        background-color: #fcfdfe;
        border-top: 1px solid rgba(77, 82, 89, 0.07);
        color: #8b95a5;
        padding: 10px 20px
    }

    .card-footer>*:last-child {
        margin-bottom: 0
    }

    .hover-shadow {
        -webkit-box-shadow: 0 0 35px rgba(0, 0, 0, 0.11);
        box-shadow: 0 0 35px rgba(0, 0, 0, 0.11)
    }

    .fs-10 {
        font-size: 10px !important;
    }

    h5 span {
        font-family: Roboto, sans-serif;
        font-weight: 400;
        font-size: 11px;
        color: #8b95a5;
    }

    h5 span {
        font-family: Roboto, sans-serif;
        font-weight: 400;
        font-size: 11px;
        color: #8b95a5;
    }

    h5 span {
        font-family: Roboto, sans-serif;
        font-weight: 400;
        font-size: 11px;
        color: #8b95a5;
    }

    h5 a {
        color: #8b95a5;
    }
</style>
<!-- SUB BANNER -->
<section class="sub-bnr">
    <div class="position-center-center">
        <div class="container">
            <h4>
                @if(session('language') == 'id')
                {{$institution->nama}}
                @else
                {{$institution->name}}
                @endif
            </h4>
            <img src="images/bg-sub-bnr.png" alt="">
        </div>
    </div>
</section>
<!-- Content -->
<div id="content">
    <div class="blog blog-page padding-top-50 padding-bottom-100 section">
        <div class="container">

            <!-- Row -->
            <div class="row">

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <ul class="list-group">
                        <li class="list-group-item {{Request::is('lri/anggota*')?'active':''}}"><a href="{{url('/lri/anggota/'.session('lri_id'))}}">Anggota</a></li>
                        <li class="list-group-item {{Request::is('lri/tupoksi*')?'active':''}}"><a href="{{url('/lri/tupoksi/'.session('lri_id'))}}">Tugas Pokok, Fungsi dan Ruang Lingkup Kerja</a></li>
                        <li class="list-group-item"><a href="{{url('/lri/3')}}">Nama-Nama Pusat Studi</a></li>
                        <li class="list-group-item"><a href="{{url('/lri/4')}}">Research Collaboration and Network</a></li>
                        <li class="list-group-item"><a href="{{url('/lri/5')}}">Regulation and Policy Advancy</a></li>
                        <li class="list-group-item"><a href="{{url('/lri/6')}}">International Converence, Public and Seminar</a></li>
                        <li class="list-group-item"><a href="{{url('/lri/7')}}">International Research Fellows</a></li>
                    </ul>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="card hover-shadow">
                        <div class="flexbox align-items-center px-20 pt-20">
                        </div>

                        <div class="card-body pt-1 pb-20">
                            
                            <h6>Tugas Pokok, Fungsi dan Ruang Lingkup Kerja</h6>
                            <p>
                            @if(session('language') === 'id')
                                {{$institution->deskripsi}}
                            @else
                                {{$institution->description}}
                            @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

@endsection