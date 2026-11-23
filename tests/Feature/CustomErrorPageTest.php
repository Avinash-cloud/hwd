<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CustomErrorPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Register temporary test routes to trigger 400 errors for testing
        Route::get('/_test/abort-400', function () {
            abort(400, 'Custom bad request test message');
        });

        Route::get('/api/_test/abort-400', function () {
            abort(400, 'API bad request test message');
        });
    }

    public function test_dedicated_404_page_route_returns_404_status_and_custom_view(): void
    {
        $response = $this->get('/404');

        $response->assertStatus(404);
        $response->assertViewIs('errors.404');
        $response->assertSee('Divine Path Not Found');
        $response->assertSee('404');
        $response->assertSee('Return to Home');
        $response->assertSee('Sacred Offerings');
        $response->assertSee('Devotee Support');
        $response->assertSee('+91 7042482555');
    }

    public function test_non_existent_url_renders_dedicated_404_view(): void
    {
        $response = $this->get('/non-existent-spiritual-page-'.time());

        $response->assertStatus(404);
        $response->assertViewIs('errors.404');
        $response->assertSee('Divine Path Not Found');
        $response->assertSee('404');
        $response->assertSee('पृष्ठ नहीं मिला');
    }

    public function test_http_400_bad_request_redirects_to_404_page(): void
    {
        $response = $this->get('/_test/abort-400');

        $response->assertRedirect('/404');
    }

    public function test_api_400_bad_request_does_not_redirect_and_returns_json(): void
    {
        $response = $this->getJson('/api/_test/abort-400');

        $response->assertStatus(400);
        $response->assertJsonStructure(['message']);
    }
}
