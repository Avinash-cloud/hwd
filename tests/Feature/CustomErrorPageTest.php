<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

class CustomErrorPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Register temporary test routes to trigger 400 and 500-series errors for testing
        Route::get('/_test/abort-400', function () {
            abort(400, 'Custom bad request test message');
        });

        Route::get('/api/_test/abort-400', function () {
            abort(400, 'API bad request test message');
        });

        Route::get('/_test/abort-500', function () {
            abort(500, 'Sensitive database credential text should not appear');
        });

        Route::get('/_test/abort-502', function () {
            abort(502, 'Bad upstream gateway');
        });

        Route::get('/_test/abort-503', function () {
            abort(503, 'Maintenance pause');
        });

        Route::get('/_test/crash-runtime', function () {
            throw new RuntimeException('SQLSTATE[HY000]: internal connection secret leaked');
        });

        Route::get('/api/_test/crash-runtime', function () {
            throw new RuntimeException('SQLSTATE[HY000]: internal api exception detail');
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

    public function test_dedicated_500_page_route_returns_soft_500_view_with_preserved_contacts(): void
    {
        $response = $this->get('/500');

        $response->assertStatus(500);
        $response->assertViewIs('errors.500');
        $response->assertSee('500');
        $response->assertSee('A Gentle Pause on Our End');
        $response->assertSee('कुछ तकनीकी बाधा आई है');
        $response->assertSee('Return to Home');
        $response->assertSee('+91 7042482555');
        $response->assertSee('sales@haridwarbliss.com');
    }

    public function test_500_502_and_503_http_errors_render_soft_friendly_views_without_exposing_internals(): void
    {
        $res500 = $this->get('/_test/abort-500');
        $res500->assertStatus(500);
        $res500->assertViewIs('errors.500');
        $res500->assertSee('A Gentle Pause on Our End');
        $res500->assertSee('Reference ID:');
        $res500->assertDontSee('Sensitive database credential text should not appear');

        $res502 = $this->get('/_test/abort-502');
        $res502->assertStatus(502);
        $res502->assertViewIs('errors.5xx');
        $res502->assertSee('Temporary Connection Pause');

        $res503 = $this->get('/_test/abort-503');
        $res503->assertStatus(503);
        $res503->assertViewIs('errors.503');
        $res503->assertSee('Sacred Portal Under Brief Maintenance');
    }

    public function test_uncaught_runtime_exception_is_handled_softly_when_debug_is_disabled(): void
    {
        config(['app.debug' => false]);

        $webResponse = $this->get('/_test/crash-runtime');
        $webResponse->assertStatus(500);
        $webResponse->assertViewIs('errors.500');
        $webResponse->assertSee('A Gentle Pause on Our End');
        $webResponse->assertSee('HB-ERR-');
        $webResponse->assertDontSee('SQLSTATE[HY000]');
        $webResponse->assertDontSee('internal connection secret leaked');

        $apiResponse = $this->getJson('/api/_test/crash-runtime');
        $apiResponse->assertStatus(500);
        $apiResponse->assertJsonPath('success', false);
        $apiResponse->assertJsonPath('status', 500);
        $apiResponse->assertJsonStructure(['success', 'status', 'message', 'reference_id']);
        $apiResponse->assertDontSee('internal api exception detail');
    }
}
