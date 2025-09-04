<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(){
        $models = Gallery::paginate(9);
        return view('gallery.index', compact('models'));
    }
}
