<?php

namespace App\Http\Controllers;

use App\Models\SumberBerita;
use Goutte\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeIpbController extends Controller
{
    protected $client;

    // WP credentials & endpoints (sesuaikan jika perlu)
    protected $wpTokenUrl = "https://lri.ipb.ac.id/wp-json/jwt-auth/v1/token";
    protected $wpPostUrl  = "https://lri.ipb.ac.id/wp-json/wp/v2/posts";
    protected $wpMediaUrl = "https://lri.ipb.ac.id/wp-json/wp/v2/media";
    protected $wpUsername = "Admin-Web";
    protected $wpPassword = "D3qr0Th8a!KglwgXL65*Biu)";

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Main autoPost: iterate sumber berita, scrape, upload image, post to WP.
     */
    public function autoPost()
    {
        $results = [];
        Log::info("=== AutoPost Started ===");

        // login
        $token = $this->getWpToken();
        if (!$token) {
            Log::error("AutoPost aborted: gagal login ke WP.");
            return response()->json(['status' => 'Login gagal ke WP'], 500);
        }

        $sumber_beritas = SumberBerita::all();

        foreach ($sumber_beritas as $sumber) {
            $src = $sumber->url_link;
            Log::info("Processing source", ['source' => $src]);

            try {
                $news = $this->getLatestNewsWithContent($src);

                if (empty($news)) {
                    $results[] = [
                        'source' => $src,
                        'status' => 'Tidak ada berita ditemukan'
                    ];
                    Log::warning("Tidak ada berita", ['source' => $src]);
                    continue;
                }

                $latest = $news[0];

                // Filter: jika image tidak ada atau jumlah karakter content kurang dari 200
                $contentLength = mb_strlen(strip_tags($latest['content'] ?? ''));
                if (empty($latest['image']) || $contentLength < 200) {
                    $results[] = [
                        'source' => $src,
                        'status' => 'Dilewati: Image tidak ada atau jumlah karakter kurang dari 200 karakter'
                    ];
                    Log::info("Dilewati karena filter", ['source' => $src, 'length' => $contentLength]);
                    continue;
                }

                $slug = Str::slug($latest['title'] ?? '');

                // safety: jika title kosong gunakan timestamp+domain
                if (empty($slug)) {
                    $slug = Str::slug(parse_url($src, PHP_URL_HOST) . '-' . time());
                }

                // cek duplikasi — hanya anggap duplikat jika salah satu post returned punya slug persis sama
                $isDuplicate = $this->wpCheckSlugExists($token, $slug);
                if ($isDuplicate) {
                    $results[] = [
                        'source' => $src,
                        'status' => 'Sudah ada, dilewati',
                        'title' => $latest['title'] ?? null,
                        'slug'  => $slug
                    ];
                    Log::info("Sudah ada, dilewati", ['title' => $latest['title'] ?? null, 'slug' => $slug]);
                    continue;
                }

                // upload image (jika ada)
                $featuredMediaId = null;
                if (!empty($latest['image'])) {
                    $featuredMediaId = $this->uploadImageToWp($token, $latest['image'], $latest['title'] ?? null);
                }

                // posting
                $postPayload = [
                    'title'   => $latest['title'] ?? '',
                    'slug'    => $slug,
                    'status'  => 'publish',
                    'content' => $latest['content'] ?? ($latest['excerpt'] ?? ''),
                    'excerpt' => $latest['excerpt'] ?? '',
                    'meta_input' => [
                        'source_url' => $latest['link'] ?? $src
                    ],
                ];

                if ($featuredMediaId) {
                    $postPayload['featured_media'] = $featuredMediaId;
                }

                // add categories if available on sumber_berita record
                if (!empty($sumber->category_id)) {
                    $postPayload['categories'] = [$sumber->category_id];
                }

                $postResponse = Http::withToken($token)->post($this->wpPostUrl, $postPayload);

                if ($postResponse->failed()) {
                    $results[] = [
                        'source' => $src,
                        'status' => 'Gagal posting',
                        'title'  => $latest['title'] ?? null,
                        'error'  => $postResponse->json()
                    ];
                    Log::error("Gagal posting", ['source' => $src, 'error' => $postResponse->json()]);
                    continue;
                }

                $results[] = [
                    'source' => $src,
                    'status' => 'Berhasil posting',
                    'title'  => $latest['title'] ?? null,
                    'wp_response' => $postResponse->json()
                ];
                Log::info("Berhasil posting", ['title' => $latest['title'] ?? null, 'source' => $src]);

            } catch (\Exception $e) {
                $results[] = [
                    'source' => $src,
                    'status' => 'Error Exception',
                    'error'  => $e->getMessage()
                ];
                Log::error("Exception terjadi", ['source' => $src, 'error' => $e->getMessage()]);
            }

            // jeda kecil antar sumber untuk mengurangi beban
            sleep(3);
        }

        Log::info("=== AutoPost Finished ===", $results);

        return response()->json([
            'total_sources' => count($sumber_beritas),
            'results' => $results
        ]);
    }

    /**
     * Debug endpoint for single URL
     */
    public function autoPostDebug()
    {
        $testUrl = "https://pplh.ipb.ac.id/category/berita/";
        $results = [];

        Log::info("=== DEBUG AutoPost for URL Started ===", ['url' => $testUrl]);

        try {
            $news = $this->getLatestNewsWithContent($testUrl);
            Log::info("DEBUG: getLatestNewsWithContent result", ['data' => $news]);

            if (empty($news)) {
                $results['scrape_status'] = "Gagal: Tidak ada berita ditemukan";
                return response()->json($results);
            }

            $latest = $news[0];

            // Filter: jika image tidak ada atau jumlah karakter content kurang dari 200
            $contentLength = mb_strlen(strip_tags($latest['content'] ?? ''));
            if (empty($latest['image']) || $contentLength < 200) {
                $results['scrape_status'] = "Dilewati: Image tidak ada atau jumlah karakter kurang dari 200 karakter";
                $results['scraped_raw'] = $latest;
                return response()->json($results);
            }

            $results['scrape_status'] = "Berhasil mengambil data";
            $results['scraped_raw'] = $latest;

            // slug
            $slug = Str::slug($latest['title'] ?? '');
            $results['slug'] = $slug;

            // login test
            $token = $this->getWpToken();
            if (!$token) {
                $results['login_status'] = 'Gagal login';
                return response()->json($results, 500);
            }
            $results['login_status'] = 'Berhasil login';

            // check slug
            $existsRaw = $this->wpRawCheckSlug($token, $slug);
            $results['wp_slug_check_raw'] = $existsRaw;
            $exists = $this->wpCheckSlugExistsConfirmed($existsRaw, $slug);
            $results['wp_slug_check'] = $exists ? "Slug ditemukan → WP menganggap posting sudah ada" : "Slug tidak ditemukan → aman untuk posting";

            // image test
            $results['image_test'] = $latest['image'] ?? null;
            if (!empty($latest['image'])) {
                try {
                    $imageContent = file_get_contents($latest['image']);
                    $results['image_status'] = "Berhasil diambil";
                    $results['image_size'] = strlen($imageContent);
                } catch (\Exception $e) {
                    $results['image_status'] = "Gagal diambil";
                    $results['image_error'] = $e->getMessage();
                }
            }

            Log::info("=== DEBUG AutoPost Finished ===", $results);
            return response()->json($results);

        } catch (\Exception $e) {
            Log::error("DEBUG Exception", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Improved multi-site scraper:
     * - If URL is a listing (category / archive), tries to extract first article link
     * - Supports multiple selectors including Astra, Jeg, WP default, custom classes
     */
    public function getLatestNewsWithContent($url)
    {
        Log::info("Scraping URL", ['url' => $url]);

        // 1) request page
        try {
            $crawler = $this->client->request('GET', $url);
        } catch (\Exception $e) {
            Log::error("Failed request", ['url' => $url, 'error' => $e->getMessage()]);
            return [];
        }

        // 2) If page looks like listing/category — try detect first article link
        $firstArticleLink = $this->extractFirstArticleLinkFromListing($crawler);
        if ($firstArticleLink) {
            Log::info("Detected listing page; first article link found", ['link' => $firstArticleLink]);
            // request detail page
            try {
                $detailCrawler = $this->client->request('GET', $firstArticleLink);
            } catch (\Exception $e) {
                Log::error("Failed request detail", ['link' => $firstArticleLink, 'error' => $e->getMessage()]);
                return [];
            }
            return $this->buildNewsFromDetailCrawler($detailCrawler, $firstArticleLink);
        }

        // 3) If not listing, try to detect article nodes on page (single article page)
        // If page has <article> or post content, treat as detail page
        if ($crawler->filter('article')->count() || $crawler->filter('.post-content')->count() || $crawler->filter('.entry-content')->count()) {
            Log::info("Page appears to be article/detail page", ['url' => $url]);
            return $this->buildNewsFromDetailCrawler($crawler, $url);
        }

        // 4) fallback: attempt to find first link in common containers
        $link = null;
        $candidates = [
            'h2.entry-title a', // Astra
            'h3.entry-title a',
            '.jeg_post .jeg_post_title a', // Jeg theme
            '.post-item a',
            '.news-item a',
            '.entry a'
        ];
        foreach ($candidates as $sel) {
            if ($crawler->filter($sel)->count()) {
                $link = $crawler->filter($sel)->first()->attr('href');
                break;
            }
        }

        if ($link) {
            try {
                $detailCrawler = $this->client->request('GET', $link);
                return $this->buildNewsFromDetailCrawler($detailCrawler, $link);
            } catch (\Exception $e) {
                Log::error("Fallback detail request failed", ['link' => $link, 'error' => $e->getMessage()]);
                return [];
            }
        }

        // nothing found
        Log::warning("No article found on page", ['url' => $url]);
        return [];
    }

    /**
     * Given a listing page crawler, try multiple selectors to return the first article URL.
     */
    protected function extractFirstArticleLinkFromListing(Crawler $crawler)
    {
        // Common: Astra theme: h2.entry-title > a (inside article)
        if ($crawler->filter('h2.entry-title a')->count()) {
            return $crawler->filter('h2.entry-title a')->first()->attr('href');
        }

        // Jeg theme: .jeg_posts .jeg_post .jeg_post_title a
        if ($crawler->filter('.jeg_post .jeg_post_title a')->count()) {
            return $crawler->filter('.jeg_post .jeg_post_title a')->first()->attr('href');
        }

        // WP default: article .entry-title a
        if ($crawler->filter('article .entry-title a')->count()) {
            return $crawler->filter('article .entry-title a')->first()->attr('href');
        }

        // Generic: .post-item a, .news-item a, .post a
        $genericSelectors = ['.post-item a', '.news-item a', '.post a', '.entry a', '.col-md-9 article h2 a'];
        foreach ($genericSelectors as $sel) {
            if ($crawler->filter($sel)->count()) {
                return $crawler->filter($sel)->first()->attr('href');
            }
        }

        return null;
    }

    /**
     * Build news array from a detail page crawler
     */
    protected function buildNewsFromDetailCrawler(Crawler $detailCrawler, $url)
    {
        $news = [];

        // Title: prefer og:title, then h1, then .entry-title, then <title>
        $title = null;
        if ($detailCrawler->filter('meta[property="og:title"]')->count()) {
            $title = trim($detailCrawler->filter('meta[property="og:title"]')->attr('content'));
        } elseif ($detailCrawler->filter('h1')->count()) {
            $title = trim($detailCrawler->filter('h1')->first()->text());
        } elseif ($detailCrawler->filter('.entry-title')->count()) {
            $title = trim($detailCrawler->filter('.entry-title')->first()->text());
        } elseif ($detailCrawler->filter('title')->count()) {
            $pageTitle = $detailCrawler->filter('title')->text();
            $parts = preg_split('/\s*[-|—]\s*/u', $pageTitle);
            $title = trim($parts[0] ?? $pageTitle);
        }

        // Excerpt: meta description or first paragraph
        $excerpt = '';
        if ($detailCrawler->filter('meta[name="description"]')->count()) {
            $excerpt = trim($detailCrawler->filter('meta[name="description"]')->attr('content'));
        } elseif ($detailCrawler->filter('.entry-content p')->count()) {
            $excerpt = trim($detailCrawler->filter('.entry-content p')->first()->text());
        } elseif ($detailCrawler->filter('.post-content p')->count()) {
            $excerpt = trim($detailCrawler->filter('.post-content p')->first()->text());
        } elseif ($detailCrawler->filter('article p')->count()) {
            $excerpt = trim($detailCrawler->filter('article p')->first()->text());
        }

        // Content: prefer .post-content, .entry-content, article .content, article
        $content = '';
        if ($detailCrawler->filter('.post-content')->count()) {
            $content = $detailCrawler->filter('.post-content')->html();
        } elseif ($detailCrawler->filter('.entry-content')->count()) {
            $content = $detailCrawler->filter('.entry-content')->html();
        } elseif ($detailCrawler->filter('article .content')->count()) {
            $content = $detailCrawler->filter('article .content')->html();
        } elseif ($detailCrawler->filter('article')->count()) {
            // use whole article inner html
            $content = $detailCrawler->filter('article')->first()->html();
        } else {
            // fallback: full body
            $content = $detailCrawler->filter('body')->first()->html();
        }

        // Remove first image from content (we will upload as featured)
        if (!empty($content)) {
            $content = preg_replace('/<img[^>]+>/i', '', $content, 1);
        }

        // Image selection: priority og:image -> .post-thumbnail img -> first img in content -> any img in page
        $image = null;
        if ($detailCrawler->filter('meta[property="og:image"]')->count()) {
            $image = $detailCrawler->filter('meta[property="og:image"]')->attr('content');
        } elseif ($detailCrawler->filter('.post-thumbnail img')->count()) {
            $image = $detailCrawler->filter('.post-thumbnail img')->first()->attr('src');
        } elseif ($detailCrawler->filter('.entry-content img')->count()) {
            $image = $detailCrawler->filter('.entry-content img')->first()->attr('src');
        } elseif ($detailCrawler->filter('article img')->count()) {
            $image = $detailCrawler->filter('article img')->first()->attr('src');
        } elseif ($detailCrawler->filter('img')->count()) {
            $image = $detailCrawler->filter('img')->first()->attr('src');
        }

        // Clean relative URLs for images/links to absolute if needed
        $image = $this->normalizeUrl($image, $url);

        $news[] = [
            'title'   => $title ?: '',
            'image'   => $image ?: '',
            'link'    => $url,
            'excerpt' => $excerpt ?: '',
            'content' => $content ?: '',
        ];

        return $news;
    }

    /**
     * Normalize possibly relative URLs against base.
     */
    protected function normalizeUrl($maybeUrl, $base)
    {
        if (empty($maybeUrl)) return null;
        // If absolute already
        if (parse_url($maybeUrl, PHP_URL_SCHEME)) {
            return $maybeUrl;
        }
        // Build absolute
        $baseParts = parse_url($base);
        $scheme = $baseParts['scheme'] ?? 'https';
        $host = $baseParts['host'] ?? '';
        $path = $maybeUrl;
        if (strpos($path, '/') === 0) {
            return $scheme . '://' . $host . $path;
        }
        // relative path
        $basePath = $baseParts['path'] ?? '/';
        $baseDir = rtrim(dirname($basePath), '/') . '/';
        return $scheme . '://' . $host . $baseDir . ltrim($path, '/');
    }

    /**
     * Get WP JWT token
     */
    protected function getWpToken()
    {
        try {
            $loginResponse = Http::withOptions(['verify' => false])
                ->timeout(60)
                ->post($this->wpTokenUrl, [
                    'username' => $this->wpUsername,
                    'password' => $this->wpPassword,
                ]);

            if ($loginResponse->failed()) {
                Log::error("WP login failed", ['response' => $loginResponse->json()]);
                return null;
            }

            return $loginResponse->json('token');
        } catch (\Exception $e) {
            Log::error("WP login exception", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Upload image to WP and return media ID or null
     */
    protected function uploadImageToWp($token, $imageUrl, $title = null)
    {
        if (empty($imageUrl)) return null;

        try {
            // get image bytes
            $imageContent = @file_get_contents($imageUrl);
            if ($imageContent === false) {
                Log::warning("Failed to fetch image for upload", ['image' => $imageUrl]);
                return null;
            }

            $imageName = basename(parse_url($imageUrl, PHP_URL_PATH) ?: 'image.jpg');

            $imageResponse = Http::withToken($token)
                ->attach('file', $imageContent, $imageName)
                ->post($this->wpMediaUrl, [
                    'title' => $title ?: $imageName
                ]);

            if ($imageResponse->successful()) {
                $mediaId = $imageResponse->json('id');
                Log::info("Image uploaded to WP", ['image' => $imageUrl, 'media_id' => $mediaId]);
                return $mediaId;
            } else {
                Log::error("Image upload failed", ['image' => $imageUrl, 'response' => $imageResponse->json()]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Exception uploading image", ['image' => $imageUrl, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Basic check: query WP posts by slug and return raw response array
     */
    protected function wpRawCheckSlug($token, $slug)
    {
        try {
            $checkResponse = Http::withToken($token)->get($this->wpPostUrl, [
                'slug' => $slug,
                'per_page' => 5,
            ]);

            if ($checkResponse->successful()) {
                return $checkResponse->json();
            }

            return [];
        } catch (\Exception $e) {
            Log::error("wpRawCheckSlug exception", ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Safer check: verify returned posts actually contain matching slug.
     */
    protected function wpCheckSlugExists($token, $slug)
    {
        $raw = $this->wpRawCheckSlug($token, $slug);
        return $this->wpCheckSlugExistsConfirmed($raw, $slug);
    }

    protected function wpCheckSlugExistsConfirmed($rawArray, $slug)
    {
        if (empty($rawArray) || !is_array($rawArray)) return false;

        foreach ($rawArray as $post) {
            if (!empty($post['slug']) && $post['slug'] === $slug) {
                return true;
            }
        }
        return false;
    }
}
