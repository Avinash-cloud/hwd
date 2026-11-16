<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductMultiCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $catGangajal;

    private Category $catPuja;

    private Category $catHawan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->catGangajal = Category::create([
            'name' => 'Gangajal Sacred',
            'slug' => 'gangajal-sacred',
            'is_active' => true,
        ]);

        $this->catPuja = Category::create([
            'name' => 'Puja Essentials',
            'slug' => 'puja-essentials',
            'is_active' => true,
        ]);

        $this->catHawan = Category::create([
            'name' => 'Hawan & Samagri',
            'slug' => 'hawan-samagri',
            'is_active' => true,
        ]);
    }

    public function test_admin_create_page_renders_multiple_category_checkboxes(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));

        $response->assertOk();
        $response->assertSee('category_ids[]');
        $response->assertSee($this->catGangajal->name);
        $response->assertSee($this->catPuja->name);
        $response->assertSee($this->catHawan->name);
    }

    public function test_admin_can_create_product_with_multiple_categories(): void
    {
        $data = [
            'name' => 'Brass Pooja Bell and Gangajal Kit',
            'category_ids' => [$this->catGangajal->id, $this->catPuja->id, $this->catHawan->id],
            'price' => 499.00,
            'compare_price' => 599.00,
            'member_price' => 449.00,
            'stock' => 50,
            'sku' => 'BPB-GK-01',
            'hsn_code' => '22019010',
            'gst_percentage' => 18.00,
            'short_description' => 'A sacred kit with multiple categories',
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'BPB-GK-01')->first();
        $this->assertNotNull($product);

        // Primary category set
        $this->assertEquals($this->catGangajal->id, $product->category_id);

        // Pivot table syncs all 3 categories
        $this->assertCount(3, $product->categories);
        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $this->catGangajal->id,
        ]);
        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $this->catPuja->id,
        ]);
        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $this->catHawan->id,
        ]);

        $this->assertEqualsCanonicalizing(
            [$this->catGangajal->id, $this->catPuja->id, $this->catHawan->id],
            $product->category_ids
        );
    }

    public function test_admin_edit_page_renders_pre_checked_categories(): void
    {
        $product = Product::create([
            'name' => 'Test Sacred Bell',
            'category_id' => $this->catPuja->id,
            'slug' => 'test-sacred-bell',
            'price' => 299.00,
            'stock' => 20,
            'is_active' => true,
        ]);
        $product->categories()->sync([$this->catPuja->id, $this->catHawan->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.edit', $product->id));

        $response->assertOk();
        $response->assertSee('category_ids[]');
        $response->assertSee((string) $this->catPuja->id);
        $response->assertSee((string) $this->catHawan->id);
    }

    public function test_admin_can_update_product_categories(): void
    {
        $product = Product::create([
            'name' => 'Pure Dhoop Cups',
            'category_id' => $this->catGangajal->id,
            'slug' => 'pure-dhoop-cups',
            'price' => 199.00,
            'stock' => 30,
            'is_active' => true,
        ]);
        $product->categories()->sync([$this->catGangajal->id]);

        $data = [
            'name' => 'Pure Dhoop Cups Updated',
            'category_ids' => [$this->catPuja->id, $this->catHawan->id],
            'price' => 220.00,
            'stock' => 25,
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product->id), $data);

        $response->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertEquals('Pure Dhoop Cups Updated', $product->name);
        $this->assertCount(2, $product->categories);

        $this->assertDatabaseMissing('category_product', [
            'product_id' => $product->id,
            'category_id' => $this->catGangajal->id,
        ]);
        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $this->catPuja->id,
        ]);
        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $this->catHawan->id,
        ]);
    }

    public function test_backward_compatibility_with_single_category_id_param(): void
    {
        $data = [
            'name' => 'Legacy API Single Category Product',
            'category_id' => $this->catPuja->id,
            'price' => 150.00,
            'stock' => 10,
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $data);
        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Legacy API Single Category Product')->first();
        $this->assertNotNull($product);
        $this->assertEquals($this->catPuja->id, $product->category_id);
        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $this->catPuja->id,
        ]);
    }

    public function test_validation_fails_when_no_category_selected(): void
    {
        $data = [
            'name' => 'No Category Product',
            'category_ids' => [],
            'price' => 100.00,
            'stock' => 5,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $data);
        $response->assertSessionHasErrors('category_ids');
    }

    public function test_catalog_finds_products_by_secondary_category(): void
    {
        $product = Product::create([
            'name' => 'Multi Category Ganga Lamp',
            'category_id' => $this->catGangajal->id,
            'slug' => 'multi-category-ganga-lamp',
            'price' => 350.00,
            'stock' => 15,
            'is_active' => true,
        ]);
        // Assigned to secondary category Puja Essentials
        $product->categories()->sync([$this->catGangajal->id, $this->catPuja->id]);

        // Filter catalog by secondary category Puja Essentials
        $response = $this->get('/products?category='.$this->catPuja->slug);

        $response->assertOk();
        $response->assertSee('Multi Category Ganga Lamp');
    }

    public function test_predictive_search_matches_secondary_category_name(): void
    {
        $product = Product::create([
            'name' => 'Exclusive Samagri Mix',
            'category_id' => $this->catGangajal->id,
            'slug' => 'exclusive-samagri-mix',
            'price' => 250.00,
            'stock' => 10,
            'is_active' => true,
        ]);
        // Attached to Hawan & Samagri as secondary category
        $product->categories()->sync([$this->catGangajal->id, $this->catHawan->id]);

        $response = $this->getJson(route('api.search', ['q' => 'Samagri']));

        $response->assertOk();
        $json = $response->json();
        $this->assertNotEmpty($json);
        $this->assertEquals('Exclusive Samagri Mix', $json[0]['name']);
    }

    public function test_admin_index_filters_products_by_secondary_category(): void
    {
        $product = Product::create([
            'name' => 'Special Hawan Cup',
            'category_id' => $this->catGangajal->id,
            'slug' => 'special-hawan-cup',
            'price' => 180.00,
            'stock' => 12,
            'is_active' => true,
        ]);
        $product->categories()->sync([$this->catGangajal->id, $this->catHawan->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.products.index', [
            'category_id' => $this->catHawan->id,
        ]));

        $response->assertOk();
        $response->assertSee('Special Hawan Cup');
    }
}
