<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SerpApi\Api;
use ZanySoft\LaravelSerpApi\Facades\SerpApi;
use ZanySoft\LaravelSerpApi\Lib\GoogleSearch;

class ScholarController extends Controller
{
    public function index(Request $request)
    {
        $query = [
            "engine" => "google_scholar_author",
            "author_id" => "tv3YfyIAAAAJ",
        ];

        $search = new GoogleSearch('2a6cd242fa4af975fe0dac9d7cf143fd733064cefb978982d111c2a73b70acbc');
        // var_dump($search);die;
        $result = $search->get_json($query);
        // $author = $result->author;
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
    }
}
