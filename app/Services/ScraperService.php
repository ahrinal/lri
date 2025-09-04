<?php

namespace App\Services;

use Goutte\Client;

class ScraperService
{
    public function getArticles($url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $articles = [];

        $crawler->filter('.news-item')->each(function ($node) use (&$articles) {
            $articles[] = [
                'title' => $node->filter('h2')->text(),
                'content' => $node->filter('.content')->text(),
                'image' => $node->filter('img')->attr('src'),
            ];
        });

        return $articles;
    }
}
