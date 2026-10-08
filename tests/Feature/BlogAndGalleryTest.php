<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogAndGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->customer = User::factory()->create([
            'is_admin' => false,
        ]);
    }

    public function test_public_can_view_blogs_index(): void
    {
        $publishedBlog = Blog::factory()->create([
            'title' => 'Ancient Water of Haridwar',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $draftBlog = Blog::factory()->create([
            'title' => 'Unpublished Draft Article',
            'is_published' => false,
        ]);

        $response = $this->get(route('blogs.index'));

        $response->assertStatus(200);
        $response->assertSee('Ancient Water of Haridwar');
        $response->assertDontSee('Unpublished Draft Article');
    }

    public function test_public_can_search_blogs(): void
    {
        Blog::factory()->create([
            'title' => 'Spiritual Ganga Aarti',
            'content' => 'The evening aarti is majestic.',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        Blog::factory()->create([
            'title' => 'Ayurvedic Copper Benefits',
            'content' => 'Copper keeps water alkaline.',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('blogs.index', ['search' => 'Copper']));

        $response->assertStatus(200);
        $response->assertSee('Ayurvedic Copper Benefits');
        $response->assertDontSee('Spiritual Ganga Aarti');
    }

    public function test_public_can_view_single_blog_and_increments_views(): void
    {
        $blog = Blog::factory()->create([
            'title' => 'Why Har Ki Pauri is Divine',
            'slug' => 'why-har-ki-pauri-is-divine',
            'is_published' => true,
            'published_at' => now()->subDay(),
            'views_count' => 5,
        ]);

        $response = $this->get(route('blogs.show', $blog->slug));

        $response->assertStatus(200);
        $response->assertSee('Why Har Ki Pauri is Divine');
        $this->assertEquals(6, $blog->fresh()->views_count);
    }

    public function test_draft_blog_returns_404_for_public(): void
    {
        $blog = Blog::factory()->create([
            'title' => 'Secret Incomplete Post',
            'slug' => 'secret-incomplete-post',
            'is_published' => false,
        ]);

        $response = $this->get(route('blogs.show', $blog->slug));

        $response->assertStatus(404);
    }

    public function test_public_can_view_sacred_gallery(): void
    {
        $activeItem = GalleryItem::factory()->create([
            'title' => 'Haridwar Sunset Aarti',
            'is_active' => true,
        ]);

        $hiddenItem = GalleryItem::factory()->create([
            'title' => 'Hidden Staging Image',
            'is_active' => false,
        ]);

        $response = $this->get(route('gallery.index'));

        $response->assertStatus(200);
        $response->assertSee('Haridwar Sunset Aarti');
        $response->assertDontSee('Hidden Staging Image');
    }

    public function test_admin_can_view_admin_blogs_index(): void
    {
        Blog::factory()->create(['title' => 'Management Article Overview']);

        $response = $this->actingAs($this->admin)->get(route('admin.blogs.index'));

        $response->assertStatus(200);
        $response->assertSee('Management Article Overview');
    }

    public function test_admin_can_create_blog_with_upload(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('ganga_temple.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.blogs.store'), [
            'title' => 'Consecration of Holy Water at Dawn',
            'category' => 'Ganga Sanctity',
            'author_name' => 'Pandit Devratna',
            'reading_time_minutes' => 6,
            'excerpt' => 'An insightful guide on dawn water collection.',
            'content' => '<p>Detailed article content on Gangajal rituals.</p>',
            'image_file' => $image,
            'is_published' => '1',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseHas('blogs', [
            'title' => 'Consecration of Holy Water at Dawn',
            'author_name' => 'Pandit Devratna',
            'is_published' => true,
            'is_featured' => true,
        ]);

        $blog = Blog::where('title', 'Consecration of Holy Water at Dawn')->first();
        $this->assertNotNull($blog);
        Storage::disk('public')->assertExists($blog->image);
    }

    public function test_admin_can_update_and_toggle_blog(): void
    {
        $blog = Blog::factory()->create([
            'title' => 'Original Blog Title',
            'is_published' => true,
            'is_featured' => false,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.blogs.update', $blog->id), [
            'title' => 'Updated Sacred Article',
            'category' => 'Rituals & Traditions',
            'author_name' => 'Acharya Vidyadhar',
            'reading_time_minutes' => 8,
            'content' => 'Updated content text',
            'is_published' => '1',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Sacred Article',
            'is_featured' => true,
        ]);

        // Toggle published
        $this->actingAs($this->admin)->post(route('admin.blogs.togglePublished', $blog->id));
        $this->assertFalse($blog->fresh()->is_published);
    }

    public function test_admin_can_delete_blog(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.blogs.destroy', $blog->id));

        $response->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }

    public function test_admin_can_manage_gallery_items(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('aarti_darshan.jpg', 1200, 800);

        // Create
        $response = $this->actingAs($this->admin)->post(route('admin.gallery.store'), [
            'title' => 'Brahmakund Morning Aarti',
            'caption' => 'Devotees chanting Vedic hymns at dawn.',
            'media_type' => 'image',
            'category' => 'Har Ki Pauri Aarti',
            'location' => 'Har Ki Pauri Ghat',
            'sort_order' => 1,
            'image_file' => $image,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertDatabaseHas('gallery_items', [
            'title' => 'Brahmakund Morning Aarti',
            'media_type' => 'image',
        ]);

        $item = GalleryItem::where('title', 'Brahmakund Morning Aarti')->first();

        // Toggle active
        $this->actingAs($this->admin)->post(route('admin.gallery.toggleActive', $item->id));
        $this->assertFalse($item->fresh()->is_active);

        // Delete
        $this->actingAs($this->admin)->delete(route('admin.gallery.destroy', $item->id));
        $this->assertDatabaseMissing('gallery_items', ['id' => $item->id]);
    }

    public function test_customer_cannot_access_admin_blogs_or_gallery(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.blogs.index'));
        $response->assertStatus(403);

        $response2 = $this->actingAs($this->customer)->get(route('admin.gallery.index'));
        $response2->assertStatus(403);
    }
}
