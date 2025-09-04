<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function index()
    {
        $models = Publication::paginate(10);     
        return view('publication.index', compact('models'));
    }
}
