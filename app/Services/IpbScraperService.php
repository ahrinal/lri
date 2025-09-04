<?php

namespace App\Services;

use Goutte\Client;

class IpbScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getLatestNews()
    {
        $url = 'https://www.ipb.ac.id/page/semua-berita-terbaru/';
        $crawler = $this->client->request('GET', $url);

        $news = [];

        // Misalnya struktur berita di list: masing-masing dalam elemen tertentu
        $crawler->filter('.news-item, .entry, .post')->each(function ($node) use (&$news) {
            $titleNode = $node->filter('h2 a, h3 a, a');
            if ($titleNode->count()) {
                $title = trim($titleNode->text());  
                $link = $titleNode->attr('href');

                // Ambil cuplikan jika tersedia
                $excerpt = '';
                if ($node->filter('p')->count()) {
                    $excerpt = trim($node->filter('p')->first()->text());
                }

                $news[] = compact('title', 'link', 'excerpt');
            }
        });

        return $crawler;
    }

    public function getFullContent($url)
    {
        $crawler = $this->client->request('GET', $url);
        // Kumpulkan semua elemen dalam .post-content atau sesuai struktur
        $content = '';
        if ($crawler->filter('.post-content')->count()) {
            $content = $crawler->filter('.post-content')->html();
        } else {
            $content = $crawler->filter('article, .content')->html() ?? '';
        }
        return $content;
    }

    public function getLatestNewsWithContent($url)
    {
        // $url = 'https://pkspl.ipb.ac.id/news/';
        $crawler = $this->client->request('GET', $url);

        $news = [];

        // Ambil hanya 1 item pertama
        $firstNode = $crawler->filter('.news-item, .entry, .post, .col-md-9 article')->first();

        if ($firstNode->count()) {
            // Link & gambar
            $titleNode = $firstNode->filter('h1 a, h2 a, h3 a, h4 a, h5 a, a');
            $link  = $titleNode->count() ? $titleNode->attr('href') : '';

            $image = '';
            if ($firstNode->filter('img')->count()) {
                $image = $firstNode->filter('img')->first()->attr('src');
            }

            $excerpt = '';
            if ($firstNode->filter('p')->count()) {
                $excerpt = trim($firstNode->filter('p')->first()->text());
            }

            $title = '';
            $content = '';

            if (!empty($link)) {
                try {
                    $detailCrawler = $this->client->request('GET', $link);

                    // Judul
                    if ($detailCrawler->filter('meta[property="og:title"]')->count()) {
                        $title = trim($detailCrawler->filter('meta[property="og:title"]')->attr('content'));
                    } elseif ($detailCrawler->filter('h1')->count()) {
                        $title = trim($detailCrawler->filter('h1')->first()->text());
                    } elseif ($detailCrawler->filter('title')->count()) {
                        $pageTitle = $detailCrawler->filter('title')->text();
                        $parts = explode('-', $pageTitle);
                        $title = trim($parts[0]);
                    } else {
                        $parts = parse_url($link);
                        $path = $parts['path'] ?? '';
                        $slug = trim($path, '/');
                        $segments = explode('/', $slug);
                        $last = end($segments);
                        $title = ucwords(str_replace('-', ' ', $last));
                    }

                    // Konten
                    if ($detailCrawler->filter('.post-content')->count()) {
                        $content = $detailCrawler->filter('.post-content')->html();
                    } elseif ($detailCrawler->filter('.entry-content')->count()) {
                        $content = $detailCrawler->filter('.entry-content')->html();
                    } elseif ($detailCrawler->filter('article .content')->count()) {
                        $content = $detailCrawler->filter('article .content')->html();
                    }
                } catch (\Exception $e) {
                    $title = $title ?: $excerpt;
                    $content = $excerpt;
                }
            }

            $news[] = [
                'title'   => $title,
                'image'   => $image,
                'link'    => $link,
                'excerpt' => $excerpt,
                'content' => $content,
            ];
        }

        return $news;
    }


    
}
