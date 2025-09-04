<?php

namespace App\Http\Controllers;

use App\Services\ScraperService;
use App\Services\WordpressService;
class ScrapeController extends Controller
{
    public function scrapeAndPost()
    {
        $scraper = new ScraperService();
        $wp = new WordpressService();

        $articles = $scraper->getArticles('https://www.ipb.ac.id/page/semua-berita-terbaru/');

        var_dump($articles);die;

        foreach ($articles as $article) {
            $wp->createPost($article['title'], $article['content']);
        }

        return "Scraping & Posting selesai!";
    }
}
