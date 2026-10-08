<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SliderAndDynamicProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->regularUser = User::factory()->create([
            'is_admin' => false,
        ]);
    }

    public function test_homepage_renders_dynamic_sliders_from_database(): void
    {
        $slide = Slider::create([
            'title' => 'Divine Maha Shivratri Blessings From Har Ki Pauri',
            'subtitle' => 'Exclusive Sacred Darshan Offer',
            'description' => 'Experience the transcendent vibrations with fresh Brahmakund holy water.',
            'image' => 'images/haridwar-panorama-clocktower.jpg',
            'button_text' => 'Order Maha Abhishek Water',
            'button_link' => '/products',
            'badge_color' => 'amber',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Divine Maha Shivratri Blessings From Har Ki Pauri');
        $response->assertSee('Exclusive Sacred Darshan Offer');
        $response->assertSee('Order Maha Abhishek Water');
    }

    public function test_admin_can_view_sliders_index(): void
    {
        Slider::create([
            'title' => 'Test Slide 1',
            'image' => 'images/test.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.sliders.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Slide 1');
        $response->assertSee('Homepage Slider Manager');
    }

    public function test_admin_can_create_slider_with_file_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('festive_hero.jpg', 1920, 800);

        $response = $this->actingAs($this->admin)->post(route('admin.sliders.store'), [
            'title' => 'Sacred Chaitra Navratri Offerings',
            'subtitle' => '9 Days of Divine Consecration',
            'description' => 'Pure hawan cups and holy water collected at dawn.',
            'image_file' => $file,
            'button_text' => 'Explore Navratri Samagri',
            'button_link' => '/products?category=hawan-samagri',
            'badge_color' => 'rose',
            'sort_order' => 2,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.sliders.index'));
        $this->assertDatabaseHas('sliders', [
            'title' => 'Sacred Chaitra Navratri Offerings',
            'subtitle' => '9 Days of Divine Consecration',
            'badge_color' => 'rose',
        ]);

        $slider = Slider::where('title', 'Sacred Chaitra Navratri Offerings')->first();
        $this->assertNotNull($slider);
        Storage::disk('public')->assertExists($slider->image);
    }

    public function test_admin_can_update_and_toggle_slider(): void
    {
        $slider = Slider::create([
            'title' => 'Old Title',
            'subtitle' => 'Old Subtitle',
            'image' => 'images/old.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Update title
        $response = $this->actingAs($this->admin)->put(route('admin.sliders.update', $slider->id), [
            'title' => 'Updated Glorious Title',
            'subtitle' => 'Updated Subtitle',
            'image_url' => 'images/new.jpg',
            'sort_order' => 5,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.sliders.index'));
        $this->assertDatabaseHas('sliders', [
            'id' => $slider->id,
            'title' => 'Updated Glorious Title',
            'sort_order' => 5,
        ]);

        // Toggle status
        $this->actingAs($this->admin)->post(route('admin.sliders.toggle', $slider->id));
        $this->assertDatabaseHas('sliders', [
            'id' => $slider->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_slider(): void
    {
        $slider = Slider::create([
            'title' => 'Slide to delete',
            'image' => 'images/delete.jpg',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.sliders.destroy', $slider->id));

        $response->assertRedirect(route('admin.sliders.index'));
        $this->assertDatabaseMissing('sliders', [
            'id' => $slider->id,
        ]);
    }

    public function test_admin_can_create_product_with_image_upload_and_dynamic_attributes(): void
    {
        Storage::fake('public');

        $category = Category::create([
            'name' => 'Sacred Incense',
            'slug' => 'sacred-incense',
            'is_active' => true,
        ]);

        $image = UploadedFile::fake()->image('dhoop_sticks.jpg', 600, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Natural Sandalwood Dhoop Sticks (Pack of 50)',
            'price' => 179.00,
            'compare_price' => 249.00,
            'member_price' => 149.00,
            'stock' => 120,
            'sku' => 'HB-DHOOP-SD-50',
            'badge' => '28% OFF',
            'is_bestseller' => '1',
            'is_featured' => '1',
            'is_active' => '1',
            'image_file' => $image,
            'short_description' => '100% natural organic Chandan incense.',
            'description' => 'Crafted without charcoal or toxic phthalates for pure pooja prayers.',
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Natural Sandalwood Dhoop Sticks (Pack of 50)',
            'price' => 179.00,
            'compare_price' => 249.00,
            'member_price' => 149.00,
            'is_bestseller' => true,
            'is_featured' => true,
        ]);

        $product = Product::where('sku', 'HB-DHOOP-SD-50')->first();
        $this->assertNotNull($product);
        Storage::disk('public')->assertExists($product->image);
        $this->assertEquals(28, $product->discount_percentage);

        // Verify product shows on homepage bestsellers
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Natural Sandalwood Dhoop Sticks (Pack of 50)');
        $homeResponse->assertSee('28% OFF');
    }

    public function test_admin_can_toggle_product_status_and_bestseller_and_featured(): void
    {
        $category = Category::create([
            'name' => 'Puja Bells',
            'slug' => 'puja-bells',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Handcrafted Temple Brass Bell',
            'slug' => 'handcrafted-temple-brass-bell',
            'price' => 450.00,
            'stock' => 20,
            'is_active' => true,
            'is_featured' => false,
            'is_bestseller' => false,
        ]);

        // Toggle featured
        $this->actingAs($this->admin)->post(route('admin.products.toggleFeatured', $product->id));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'is_featured' => true]);

        // Toggle bestseller
        $this->actingAs($this->admin)->post(route('admin.products.toggleBestseller', $product->id));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'is_bestseller' => true]);

        // Toggle active
        $this->actingAs($this->admin)->post(route('admin.products.toggleActive', $product->id));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'is_active' => false]);
    }

    public function test_guest_and_non_admin_cannot_access_sliders_management(): void
    {
        // Guest
        $this->get(route('admin.sliders.index'))->assertRedirect(route('login'));

        // Non-admin user
        $this->actingAs($this->regularUser)->get(route('admin.sliders.index'))->assertStatus(403);
    }
}
