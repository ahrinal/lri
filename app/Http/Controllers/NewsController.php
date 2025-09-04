<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $models = News::paginate(5);
        // $model = News::get();
        return view('news.index', compact('models'));
    }
    public function detail($id)
    {
        $news = News::findOrFail($id);
        $latest_news = News::whereIn('id', function ($query) {
            $query->selectRaw("SUBSTRING_INDEX(GROUP_CONCAT(id ORDER BY created_at DESC), ',', 2)")
                  ->from("news")
                  ->groupBy("institution_id");
        })->get();
        return view('news.detail', compact('news', 'latest_news'));
    }
}
