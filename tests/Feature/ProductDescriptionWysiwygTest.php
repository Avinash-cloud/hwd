<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDescriptionWysiwygTest extends TestCase
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

    public function test_admin_product_create_and_edit_renders_wysiwyg_editor(): void
    {
        // 1. Create page
        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));
        $response->assertOk();
        $response->assertSee('x-ref="quillDiv"', false);
        $response->assertSee('quill.snow.css', false);
        $response->assertSee('quill.js', false);
        $response->assertSee('Rich Text Editor (Headings, Lists, Bold, Links)', false);
        $response->assertSee('name="description"', false);

        // 2. Edit page with existing product
        $product = Product::create([
            'name' => 'Sacred Hawan Cups',
            'category_id' => $this->category->id,
            'slug' => 'sacred-hawan-cups',
            'price' => 199.00,
            'stock' => 50,
            'description' => '<p>Initial <strong>pure</strong> content.</p>',
            'is_active' => true,
        ]);

        $editResponse = $this->actingAs($this->admin)->get(route('admin.products.edit', $product->id));
        $editResponse->assertOk();
        $editResponse->assertSee('x-ref="quillDiv"', false);
        $editResponse->assertSee('<p>Initial <strong>pure</strong> content.</p>', false);
    }

    public function test_admin_can_save_and_update_html_formatted_product_description(): void
    {
        $formattedHtml = '<h2>Vedic Origins</h2><p>Collected at <strong>Brahma Muhurta</strong> with devotion.</p><ul><li>Untouched bottling</li><li>Lab certified</li></ul>';

        // 1. Create product with rich HTML description
        $createData = [
            'name' => 'Pure Consecrated Gangajal',
            'category_ids' => [$this->category->id],
            'price' => 249.00,
            'stock' => 100,
            'sku' => 'PCG-001',
            'description' => $formattedHtml,
            'is_active' => 1,
        ];

        $createResponse = $this->actingAs($this->admin)->post(route('admin.products.store'), $createData);
        $createResponse->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'PCG-001')->first();
        $this->assertNotNull($product);
        $this->assertEquals($formattedHtml, $product->description);

        // 2. Update product description with new HTML
        $updatedHtml = '<h3>Sacred Benefits</h3><p>Ideal for <em>daily pooja</em> and abhishek.</p><ol><li>Purify altar</li><li>Chant mantra</li></ol>';

        $updateData = [
            'name' => 'Pure Consecrated Gangajal Updated',
            'category_ids' => [$this->category->id],
            'price' => 249.00,
            'stock' => 100,
            'sku' => 'PCG-001',
            'description' => $updatedHtml,
            'is_active' => 1,
        ];

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.products.update', $product->id), $updateData);
        $updateResponse->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertEquals($updatedHtml, $product->description);
    }

    public function test_product_detail_page_renders_rich_html_formatted_description(): void
    {
        $product = Product::create([
            'name' => 'Brahma Muhurta Gangajal Special',
            'category_id' => $this->category->id,
            'slug' => 'brahma-muhurta-gangajal-special',
            'price' => 349.00,
            'stock' => 25,
            'description' => '<h2>Divine Purity</h2><p>Untouched holy water from <strong>Har Ki Pauri</strong>.</p><ul><li>Lab certified minerals</li><li>Hermetically sealed</li></ul>',
            'is_active' => true,
        ]);

        $response = $this->get('/products/'.$product->slug);

        $response->assertOk();
        $response->assertSee('wysiwyg-content', false);
        // HTML elements should be rendered unescaped in the document
        $response->assertSee('<h2>Divine Purity</h2>', false);
        $response->assertSee('<strong>Har Ki Pauri</strong>', false);
        $response->assertSee('<li>Lab certified minerals</li>', false);
        // Should NOT display escaped raw tags
        $response->assertDontSee('&lt;h2&gt;Divine Purity&lt;/h2&gt;', false);
    }

    public function test_product_detail_page_gracefully_handles_legacy_plain_text_description(): void
    {
        $plainText = "Traditional pure dhoop cones.\nMade with natural herbs and desi cow ghee.\nNo charcoal or chemical fragrances.";

        $product = Product::create([
            'name' => 'Legacy Plain Dhoop',
            'category_id' => $this->category->id,
            'slug' => 'legacy-plain-dhoop',
            'price' => 129.00,
            'stock' => 40,
            'description' => $plainText,
            'is_active' => true,
        ]);

        $response = $this->get('/products/'.$product->slug);

        $response->assertOk();
        $response->assertSee('wysiwyg-content', false);
        $response->assertSee('whitespace-pre-line', false);
        $response->assertSee('Traditional pure dhoop cones.');
    }
}
