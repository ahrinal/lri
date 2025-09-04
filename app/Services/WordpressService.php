<?php

namespace App\Services;

use GuzzleHttp\Client;

class WordpressService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => rtrim(env('WP_URL'), '/') . '/wp-json/wp/v2/',
            'auth' => [env('WP_USER'), env('WP_APP_PASS')],
        ]);
    }

    public function createPost($title, $content, $status = 'publish')
    {
        $response = $this->client->post('posts', [
            'json' => [
                'title'   => $title,
                'content' => $content,
                'status'  => $status,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }
}
