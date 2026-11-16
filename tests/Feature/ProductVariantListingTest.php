<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantListingTest extends TestCase
{
    use RefreshDatabase;

    private function createProductWithVariants(): Product
    {
        $category = Category::create([
            'name' => 'Sacred Water',
            'slug' => 'sacred-water',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brahmakund Haridwar Gangajal',
            'slug' => 'brahmakund-haridwar-gangajal',
            'sku' => 'BK-GANGAJAL',
            'price' => 99.00,
            'stock' => 100,
            'is_active' => true,
            'is_gangajal' => true,
            'is_bestseller' => true,
            'short_description' => 'Consecrated hermetic bottled Gangajal',
            'description' => 'Detailed description',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => '100ml',
            'sku' => 'BK-100ML',
            'price' => 99.00,
            'compare_price' => 149.00,
            'stock' => 20,
            'volume_ml' => 100,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => '500ml',
            'sku' => 'BK-500ML',
            'price' => 249.00,
            'compare_price' => 299.00,
            'stock' => 30,
            'volume_ml' => 500,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => '1000ml',
            'sku' => 'BK-1000ML',
            'price' => 399.00,
            'compare_price' => 499.00,
            'stock' => 40,
            'volume_ml' => 1000,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        return $product;
    }

    public function test_product_archive_expands_variants_into_individual_product_cards(): void
    {
        $product = $this->createProductWithVariants();

        $response = $this->get('/products');

        $response->assertStatus(200);

        // Verify all 3 variants are displayed on the archive
        $response->assertSee('Brahmakund Haridwar Gangajal (100ml)');
        $response->assertSee('Brahmakund Haridwar Gangajal (500ml)');
        $response->assertSee('Brahmakund Haridwar Gangajal (1000ml)');

        // Verify prices for each variant appear
        $response->assertSee('₹99');
        $response->assertSee('₹249');
        $response->assertSee('₹399');

        // Verify variant query parameters in links
        $variants = $product->variants;
        $response->assertSee("variant={$variants[0]->id}");
        $response->assertSee("variant={$variants[1]->id}");
        $response->assertSee("variant={$variants[2]->id}");
    }

    public function test_homepage_stream_displays_individual_variant_cards(): void
    {
        $product = $this->createProductWithVariants();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Brahmakund Haridwar Gangajal (100ml)');
        $response->assertSee('Brahmakund Haridwar Gangajal (500ml)');
        $response->assertSee('Brahmakund Haridwar Gangajal (1000ml)');
    }

    public function test_single_product_page_preselects_active_variant_from_query_parameter(): void
    {
        $product = $this->createProductWithVariants();
        $targetVariant = $product->variants->where('name', '500ml')->first();

        $response = $this->get("/products/{$product->slug}?variant={$targetVariant->id}");

        $response->assertStatus(200);

        // activeVariantId in Alpine.js should be the 500ml variant id
        $response->assertSee("activeVariantId: {$targetVariant->id}", false);
    }

    public function test_single_product_page_preselects_active_variant_by_volume(): void
    {
        $product = $this->createProductWithVariants();
        $targetVariant = $product->variants->where('name', '1000ml')->first();

        // Testing volume matching ?variant=1000
        $response = $this->get("/products/{$product->slug}?variant=1000");

        $response->assertStatus(200);
        $response->assertSee("activeVariantId: {$targetVariant->id}", false);
    }
}
