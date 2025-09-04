<?php

namespace App\Http\Controllers;

use App\Models\SumberBerita;
use App\Services\IpbScraperService;
use App\Services\WordpressService;
use Goutte\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ScrapeIpbController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function autoPost()
    {
        $loginResponse = Http::withOptions(['verify' => false])
        ->timeout(60)
        ->post("https://lri.ipb.ac.id/wp-json/jwt-auth/v1/token", [
            'username' => "Admin-Web",
            'password' => "D3qr0Th8a!KglwgXL65*Biu)",
        ]);

        $token = $loginResponse->json('token');
        $sumber_beritas = SumberBerita::all();
        $postUrl = "https://lri.ipb.ac.id/wp-json/wp/v2/posts";
        foreach($sumber_beritas as $sumber_berita){
            try{
                $news = $this->getLatestNewsWithContent($sumber_berita->url_link);
                if (empty($news)) {
                    $results[] = [
                        'source' => $sumber_berita->url_link,
                        'status' => 'Tidak ada berita ditemukan'
                    ];
                    continue;
                }
                $latest = $news[0];
                $slug = Str::slug($latest['title']);

                // --- 3. Cek duplikat ---
                $checkResponse = Http::withToken($token)->get($postUrl, [
                    'slug' => $slug,
                    'per_page' => 1,
                ]);

                if ($checkResponse->successful() && !empty($checkResponse->json())) {
                    $results[] = [
                        'source' => $sumber_berita->url_link,
                        'status' => 'Sudah ada, dilewati',
                        'title' => $latest['title']
                    ];
                    continue;
                }

                // --- 4. Upload image ---
                $featuredMediaId = null;
                if (!empty($latest['image'])) {
                    try {
                        $imageContent = file_get_contents($latest['image']);
                        $imageName = basename($latest['image']);

                        $imageResponse = Http::withToken($token)
                            ->attach('file', $imageContent, $imageName)
                            ->post("https://lri.ipb.ac.id/wp-json/wp/v2/media", [
                                'title' => $latest['title']
                            ]);

                        if ($imageResponse->successful()) {
                            $featuredMediaId = $imageResponse->json('id');
                        }
                    } catch (\Exception $e) {
                        $featuredMediaId = null;
                    }
                }
                // --- 5. Posting ke WP ---
                $postResponse = Http::withToken($token)->post($postUrl, [
                    'title'   => $latest['title'],
                    'slug'    => $slug,
                    'status'  => 'publish',
                    'content' => $latest['content'],
                    'excerpt' => $latest['excerpt'],
                    'featured_media' => $featuredMediaId,
                    'meta_input' => [
                        'source_url' => $latest['link'],
                    ],
                    'categories' => [$sumber_berita->category_id]
                ]);

                if ($postResponse->failed()) {
                    $results[] = [
                        'source' => $sumber_berita->url_link,
                        'status' => 'Gagal posting',
                        'error'  => $postResponse->json()
                    ];
                    continue;
                }

                $results[] = [
                    'source' => $sumber_berita->url_link,
                    'status' => 'Berhasil posting',
                    'title'  => $latest['title']
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'source' => $sumber_berita->url_link,
                    'status' => 'Error',
                    'error'  => $e->getMessage()
                ];
            }
            sleep(60); // jeda 60 detik
        }
    }
    
    public function scrapeAndPostToWordpress($url)
    {
        // 1. Ambil berita terbaru
        // $sumber_beritas = SumberBerita::all();
        // var_dump($sumber_berita);die;
        $news = $this->getLatestNewsWithContent($url);
        $postUrl = "https://lri.ipb.ac.id/wp-json/wp/v2/posts";
        if (empty($news)) {
            return response()->json(['error' => 'Tidak ada berita ditemukan'], 404);
        }

        $latest = $news[0]; // karena kita ambil 1 berita saja

        // 2. Login ke WP via JWT
        $wpUrl = "https://lri.ipb.ac.id/wp-json/jwt-auth/v1/token";
        $username = "Admin-Web";   // ganti username WP
        $password = "D3qr0Th8a!KglwgXL65*Biu)"; // ganti password WP

        $loginResponse = Http::post($wpUrl, [
            'username' => $username,
            'password' => $password,
        ]);

        if ($loginResponse->failed()) {
            return response()->json(['error' => 'Login ke WordPress gagal', 'response' => $loginResponse->json()], 500);
        }

        $token = $loginResponse->json('token');

        // 3. Cek apakah postingan dengan judul yang sama sudah ada (pakai slug)
        $slug = Str::slug($latest['title']); // pastikan tambahkan use Illuminate\Support\Str;
        $checkResponse = Http::withToken($token)->get($postUrl, [
            'slug' => $slug,
            'per_page' => 1,
        ]);

        if ($checkResponse->successful()) {
            $existingPosts = $checkResponse->json();
            if (!empty($existingPosts)) {
                return response()->json([
                    'message' => 'Berita dengan judul ini sudah ada (slug match), dilewati.',
                    'title'   => $latest['title']
                ]);
            }
        }

        // 4. Upload image jika ada
        $featuredMediaId = null;

        if (!empty($latest['image'])) {
            try {
                // Ambil isi file gambar ukuran besar
                $imageContent = file_get_contents($latest['image']);
                $imageName = basename($latest['image']);

                $imageResponse = Http::withToken($token)
                    ->attach('file', $imageContent, $imageName)
                    ->post("https://lri.ipb.ac.id/wp-json/wp/v2/media", [
                        'title' => $latest['title']
                    ]);

                if ($imageResponse->successful()) {
                    $featuredMediaId = $imageResponse->json('id');
                }
            } catch (\Exception $e) {
                // gagal upload image, biarkan saja tanpa featured image
                $featuredMediaId = null;
            }
        }

        // 5. Posting berita ke WP

        $postResponse = Http::withToken($token)->post($postUrl, [
            'title'   => $latest['title'],
            'slug'    => $slug,
            'status'  => 'publish', // bisa 'draft' kalau mau review dulu
            'content' => $latest['content'],
            'excerpt' => $latest['excerpt'],
            'featured_media' => $featuredMediaId, // kalau null, otomatis skip
            'meta_input' => [
                'source_url' => $latest['link'], // simpan sumber berita
            ]
        ]);

        if ($postResponse->failed()) {
            return response()->json(['error' => 'Gagal posting ke WordPress', 'response' => $postResponse->json()], 500);
        }

        return response()->json([
            'message' => 'Berita berhasil diposting ke WordPress!',
            // 'wp_response' => $postResponse->json(),
        ]);
    }

    public function getLatestNewsWithContent($url)
    {
        // $url = 'https://biotech-center.ipb.ac.id/berita-acara/';
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
                    // Hapus <img> pertama biar tidak double dengan featured image
                    if (!empty($content)) {
                        $content = preg_replace('/<img[^>]+>/i', '', $content, 1);
                    }

                    // Cari gambar besar dari halaman detail
                    if ($detailCrawler->filter('meta[property="og:image"]')->count()) {
                        $image = $detailCrawler->filter('meta[property="og:image"]')->attr('content');
                    } elseif ($detailCrawler->filter('.post-content img')->count()) {
                        $image = $detailCrawler->filter('.post-content img')->first()->attr('src');
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

    public function scrapeAndPost()
    {
        $scraper = new IpbScraperService();
        $wp = new WordpressService();

        // $newsList = $scraper->getFullContent("https://www.ipb.ac.id/news/index/2025/08/ipb-university-wisuda-186-fasilitator-sekolah-keluarga-berkualitas/");
        $newsList = $scraper->getLatestNewsWithContent();
        echo "<pre>";
        // echo "tes";
        var_dump($newsList);die;
        echo "</pre>";

        foreach ($newsList as $news) {
            // (Opsional) ambil konten penuh dari halaman detail:
            $fullContent = $scraper->getFullContent($news['link']);

            // Jika konten detail kosong, fallback ke excerpt:
            $content = $fullContent ?: $news['excerpt'];

            $result = $wp->createPost($news['title'], $content);

            \Log::info("Posted: {$news['title']} → ID {$result['id']}");
        }

        return response()->json([
            'message' => 'Scraping & posting selesai.',
            'count' => count($newsList),
            'titles' => array_column($newsList, 'title'),
        ]);
    }
}
