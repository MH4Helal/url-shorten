<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    /** @test */
    public function it_encodes_a_url()
    {
        $response = $this->post('/encode', ['url' => 'https://www.example.com/long/url']);

        $response->assertStatus(200)
            ->assertJsonStructure(['short_url']);

        $shortUrl = $response->json('short_url');
        $this->assertStringContainsString('/short/', $shortUrl);
    }

    /** @test */
    public function it_decodes_a_url()
    {
        $encodeResponse = $this->post('/encode', ['url' => 'https://www.example.com/long/url']);
        $shortUrl = $encodeResponse->json('short_url');
        $shortCode = substr($shortUrl, strrpos($shortUrl, '/') + 1);

        $decodeResponse = $this->get('/decode/' . $shortCode);

        $decodeResponse->assertStatus(200)
            ->assertJson(['original_url' => 'https://www.example.com/long/url']);
    }

    /** @test */
    public function it_returns_404_when_short_url_not_found()
    {
        $response = $this->get('/decode/nonexistent');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Short URL not found']);
    }

    /** @test */
    public function it_redirects_correctly()
    {
        $encodeResponse = $this->post('/encode', ['url' => 'https://www.example.com/long/url']);
        $shortUrl = $encodeResponse->json('short_url');
        $shortCode = substr($shortUrl, strrpos($shortUrl, '/') + 1);

        $response = $this->get('/short/'.$shortCode);
        $response->assertRedirect('https://www.example.com/long/url');
    }

    /** @test */
    public function it_returns_404_when_redirecting_nonexistent()
    {
        $response = $this->get('/short/nonexistent');
        $response->assertStatus(404);
    }

    /** @test */
    public function it_validates_url_on_encode()
    {
        $response = $this->post('/encode', ['url' => 'invalid-url']);
        $response->assertStatus(400);
    }
}