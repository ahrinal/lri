<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Publication;
use App\Models\Slider;
use App\Models\SlideShow;
use App\Models\Tautan;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
            
        $news = News::orderBy('id', 'desc')->take(3)->get();
        $slider = Slider::get();
        $publications = Publication::orderBy('year', 'desc')->take(10)->get();

        return view('home',compact('news', 'slider', 'publications'));
    }
}
