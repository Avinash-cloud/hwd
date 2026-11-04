<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductMediaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Puja Essentials',
            'slug' => 'puja-essentials',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_product_create_and_edit_pages_with_media_inputs(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));
        $response->assertOk();
        $response->assertSee('1. General Info');
        $response->assertSee('2. Pricing & Stock', false);
        $response->assertSee('3. Media & Gallery', false);
        $response->assertSee('4. Descriptions & Details', false);
        $response->assertSee('admin_product_form_tab');
        $response->assertSee('gallery_files[]');
        $response->assertSee('gallery_urls');
        $response->assertSee('video_url');
        $response->assertSee('video_file');

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 199.00,
            'stock' => 10,
            'image' => '/images/products/ghee-diya-batti.jpg',
            'gallery' => ['/images/products/ghee-diya-batti.jpg', '/images/products/akhand-jyoti-diya.jpg'],
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_active' => true,
        ]);

        $editResponse = $this->actingAs($this->admin)->get(route('admin.products.edit', $product));
        $editResponse->assertOk();
        $editResponse->assertSee('1. General Info');
        $editResponse->assertSee('2. Pricing & Stock', false);
        $editResponse->assertSee('3. Media & Gallery', false);
        $editResponse->assertSee('4. Descriptions & Details', false);
        $editResponse->assertSee('admin_product_form_tab');
        $editResponse->assertSee('keep_gallery[]');
        $editResponse->assertSee('remove_video');
    }

    public function test_admin_can_store_product_with_multiple_gallery_images_and_video(): void
    {
        Storage::fake('public');

        $coverFile = UploadedFile::fake()->image('cover.jpg', 600, 600);
        $galleryFile1 = UploadedFile::fake()->image('gallery1.jpg', 600, 600);
        $galleryFile2 = UploadedFile::fake()->image('gallery2.jpg', 600, 600);

        $payload = [
            'category_id' => $this->category->id,
            'name' => 'Sacred Brass Akhand Diya with Glass',
            'price' => 499.00,
            'compare_price' => 699.00,
            'stock' => 50,
            'image_file' => $coverFile,
            'gallery_files' => [$galleryFile1, $galleryFile2],
            'gallery_urls' => "https://example.com/extra-photo1.jpg\nhttps://example.com/extra-photo2.jpg",
            'video_url' => 'https://www.youtube.com/watch?v=sample12345',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $payload);
        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Sacred Brass Akhand Diya with Glass')->first();
        $this->assertNotNull($product);

        // Assert cover photo stored
        Storage::disk('public')->assertExists($product->image);

        // Assert gallery images stored
        $this->assertIsArray($product->gallery);
        $this->assertCount(4, $product->gallery); // 2 uploaded files + 2 URLs
        $this->assertContains('https://example.com/extra-photo1.jpg', $product->gallery);
        $this->assertContains('https://example.com/extra-photo2.jpg', $product->gallery);

        // Assert video saved
        $this->assertEquals('https://www.youtube.com/watch?v=sample12345', $product->video_url);
        $this->assertTrue($product->is_video_embed);
        $this->assertStringContainsString('sample12345', $product->video_embed_url);
    }

    public function test_admin_can_update_product_gallery_and_video(): void
    {
        Storage::fake('public');

        $initialProduct = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Original Diya',
            'slug' => 'original-diya',
            'price' => 499.00,
            'stock' => 20,
            'image' => '/images/products/initial.jpg',
            'gallery' => [
                'https://example.com/keep-this.jpg',
                'https://example.com/remove-this.jpg',
            ],
            'video_url' => 'https://www.youtube.com/watch?v=oldvideo',
            'is_active' => true,
        ]);

        $newGalleryFile = UploadedFile::fake()->image('new-angle.jpg', 600, 600);

        $payload = [
            'category_id' => $this->category->id,
            'name' => $initialProduct->name,
            'price' => 499.00,
            'stock' => 20,
            'keep_gallery' => ['https://example.com/keep-this.jpg'], // Unchecked 'remove-this.jpg'
            'gallery_files' => [$newGalleryFile],
            'gallery_urls' => 'https://example.com/added-url.jpg',
            'video_url' => 'https://vimeo.com/987654321',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $initialProduct), $payload);
        $response->assertRedirect(route('admin.products.index'));

        $initialProduct->refresh();

        $this->assertContains('https://example.com/keep-this.jpg', $initialProduct->gallery);
        $this->assertNotContains('https://example.com/remove-this.jpg', $initialProduct->gallery);
        $this->assertContains('https://example.com/added-url.jpg', $initialProduct->gallery);
        $this->assertCount(3, $initialProduct->gallery);

        $this->assertEquals('https://vimeo.com/987654321', $initialProduct->video_url);
        $this->assertStringContainsString('player.vimeo.com/video/987654321', $initialProduct->video_embed_url);
    }

    public function test_product_detail_page_shows_gallery_and_video_viewer(): void
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Pure Vedic Samagri Box',
            'slug' => 'pure-vedic-samagri-box',
            'price' => 599.00,
            'stock' => 15,
            'image' => '/images/products/rudrabhishek-puja-kit.jpg',
            'gallery' => [
                '/images/products/ghee-diya-batti.jpg',
                '/images/products/akhand-jyoti-diya.jpg',
            ],
            'video_url' => 'https://www.youtube.com/watch?v=demoVideo123',
            'is_active' => true,
        ]);

        $response = $this->get(route('products.show', $product->slug));
        $response->assertOk();
        $response->assertSee('Pure Vedic Samagri Box');
        $response->assertSee('Watch Video');
        $response->assertSee('youtube.com/embed/demoVideo123');
        $response->assertSee('/images/products/ghee-diya-batti.jpg');
    }
}
