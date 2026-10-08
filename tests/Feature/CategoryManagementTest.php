<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
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

    public function test_guest_cannot_access_admin_categories(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_admin_categories(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.categories.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_category_index_and_search(): void
    {
        Category::create([
            'name' => 'Sacred Hawan Kund',
            'slug' => 'sacred-hawan-kund',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Rudraksha Rosaries',
            'slug' => 'rudraksha-rosaries',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index', ['search' => 'Hawan']));

        $response->assertStatus(200);
        $response->assertSee('Sacred Hawan Kund');
        $response->assertDontSee('rudraksha-rosaries');
    }

    public function test_admin_can_view_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertSee('Add New Offering Category');
    }

    public function test_admin_can_create_category_with_uploaded_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('dhoop_batti.jpg', 600, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Organic Cow Dung Dhoop',
            'slug' => 'organic-cow-dung-dhoop',
            'icon' => '🌿',
            'description' => 'Made from Gir cow dung and Ayurvedic herbs.',
            'image_file' => $file,
            'sort_order' => 5,
            'is_featured' => '1',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Organic Cow Dung Dhoop',
            'slug' => 'organic-cow-dung-dhoop',
            'is_featured' => true,
            'is_active' => true,
            'icon' => '🌿',
        ]);

        $category = Category::where('slug', 'organic-cow-dung-dhoop')->first();
        $this->assertNotNull($category);
        Storage::disk('public')->assertExists($category->image);
    }

    public function test_admin_can_create_sub_category(): void
    {
        $parent = Category::create([
            'name' => 'Puja Essentials',
            'slug' => 'puja-essentials',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Camphor & Kapoor Tablets',
            'parent_id' => $parent->id,
            'icon' => '✨',
            'sort_order' => 1,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Camphor & Kapoor Tablets',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::create([
            'name' => 'Old Category Name',
            'slug' => 'old-category-name',
            'is_active' => true,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Updated Sacred Name',
            'slug' => 'updated-sacred-name',
            'icon' => '🕉️',
            'description' => 'Newly updated description',
            'is_featured' => '1',
            'is_active' => '1',
            'sort_order' => 10,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Sacred Name',
            'slug' => 'updated-sacred-name',
            'icon' => '🕉️',
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_toggle_active_and_featured_states(): void
    {
        $category = Category::create([
            'name' => 'Maha Deepam',
            'slug' => 'maha-deepam',
            'is_active' => true,
            'is_featured' => false,
        ]);

        // Toggle featured
        $response = $this->actingAs($this->admin)->post(route('admin.categories.toggleFeatured', $category));
        $response->assertRedirect();
        $this->assertTrue($category->fresh()->is_featured);

        // Toggle active
        $response = $this->actingAs($this->admin)->post(route('admin.categories.toggleActive', $category));
        $response->assertRedirect();
        $this->assertFalse($category->fresh()->is_active);
    }

    public function test_admin_cannot_delete_category_with_products(): void
    {
        $category = Category::create([
            'name' => 'Protected Category',
            'slug' => 'protected-category',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Sample Pooja Product',
            'slug' => 'sample-pooja-product',
            'price' => 199,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_delete_empty_category_and_unlink_child_categories(): void
    {
        $parent = Category::create([
            'name' => 'Parent Collection',
            'slug' => 'parent-collection',
            'is_active' => true,
        ]);

        $child = Category::create([
            'name' => 'Child Collection',
            'slug' => 'child-collection',
            'parent_id' => $parent->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $parent));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $parent->id]);
        $this->assertNull($child->fresh()->parent_id);
    }
}
