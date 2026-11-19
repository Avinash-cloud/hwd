<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WaterCollectionLead;
use App\Models\WaterCollectionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaterCollectionTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'email' => 'admin@test.com',
            'is_admin' => true,
        ]);
    }

    private function createCustomer(): User
    {
        return User::factory()->create([
            'email' => 'customer@test.com',
            'is_admin' => false,
        ]);
    }

    public function test_water_collection_page_is_accessible_and_seeds_default_packages(): void
    {
        $response = $this->get(route('water-collection'));

        $response->assertStatus(200);
        $response->assertSee('Haridwar Kumbh Water Collection Service');
        $response->assertSee('हरिद्वार महाकुम्भ पवित्र गंगाजल संग्रह एवं देवस्थान होम डिलीवरी');
        $response->assertSee('1 Litre Sacred Kumbh Ganga Jal');
        $response->assertSee('Hotel / Dharamshala / Ashram Name');
        $response->assertSee('Resident Street Address');

        $this->assertDatabaseHas('water_collection_packages', [
            'name' => '1 Litre Sacred Kumbh Ganga Jal',
            'is_active' => true,
        ]);
    }

    public function test_devotee_can_submit_kumbh_water_collection_lead(): void
    {
        WaterCollectionPackage::seedDefaultPackagesIfEmpty();
        $package = WaterCollectionPackage::first();

        $postData = [
            'name' => 'Rajesh Kumar Sharma',
            'mobile' => '+91 9876543210',
            'email' => 'rajesh.sharma@example.com',
            'delivery_address' => 'Flat 402, Shiv Ganga Apartments, Sector 62',
            'delivery_city' => 'Noida',
            'delivery_state' => 'Uttar Pradesh',
            'delivery_pincode' => '201309',
            'hotel_or_dharamshala' => 'Hotel Ganga Kinare, Haridwar',
            'pickup_room_or_landmark' => 'Room 204, Near Haridwar Station',
            'package_id' => $package->id,
            'quantity_count' => 2,
            'pickup_date' => now()->addDays(2)->format('Y-m-d'),
            'notes' => 'Shandilya Gotra, Auspicious Somvati Amavasya snan jal sankalp.',
        ];

        $response = $this->post(route('water-collection.store'), $postData);

        $lead = WaterCollectionLead::where('mobile', '+91 9876543210')->first();
        $this->assertNotNull($lead);
        $this->assertEquals('Rajesh Kumar Sharma', $lead->name);
        $this->assertEquals('Hotel Ganga Kinare, Haridwar', $lead->hotel_or_dharamshala);
        $this->assertEquals($package->price * 2, (float) $lead->total_price);
        $this->assertEquals('pending', $lead->status);

        $response->assertRedirect(route('water-collection.confirmed', ['lead_number' => $lead->lead_number]));

        // Visit the confirmed page
        $confirmedResponse = $this->get(route('water-collection.confirmed', ['lead_number' => $lead->lead_number]));
        $confirmedResponse->assertStatus(200);
        $confirmedResponse->assertSee($lead->lead_number);
        $confirmedResponse->assertSee('Hotel Ganga Kinare, Haridwar');
        $confirmedResponse->assertSee('Flat 402, Shiv Ganga Apartments');
    }

    public function test_water_collection_lead_validation_errors(): void
    {
        $response = $this->post(route('water-collection.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'mobile',
            'delivery_address',
            'hotel_or_dharamshala',
            'package_id',
            'quantity_count',
        ]);
    }

    public function test_admin_can_view_and_filter_water_collection_leads(): void
    {
        $admin = $this->createAdmin();

        $lead = WaterCollectionLead::create([
            'lead_number' => 'KMB-20261119-TEST',
            'name' => 'Smt. Sunita Verma',
            'mobile' => '+91 9811223344',
            'email' => 'sunita@example.com',
            'delivery_address' => 'House 12, Civil Lines',
            'delivery_city' => 'Jaipur',
            'delivery_state' => 'Rajasthan',
            'delivery_pincode' => '302006',
            'hotel_or_dharamshala' => 'Birla Dharamshala, Haridwar',
            'pickup_room_or_landmark' => 'Block B Room 12',
            'package_name' => '5 Litres Maha Kumbh Pavitra Canister',
            'water_quantity' => '5 Litres',
            'quantity_count' => 1,
            'unit_price' => 501.00,
            'total_price' => 501.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.water-collection-leads.index'));

        $response->assertStatus(200);
        $response->assertSee('KMB-20261119-TEST');
        $response->assertSee('Smt. Sunita Verma');
        $response->assertSee('Birla Dharamshala, Haridwar');

        // Show page
        $showResponse = $this->actingAs($admin)->get(route('admin.water-collection-leads.show', $lead));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Smt. Sunita Verma');
        $showResponse->assertSee('Birla Dharamshala, Haridwar');
        $showResponse->assertSee('House 12, Civil Lines');
    }

    public function test_admin_can_update_lead_status_and_notes(): void
    {
        $admin = $this->createAdmin();

        $lead = WaterCollectionLead::create([
            'lead_number' => 'KMB-20261119-UPDT',
            'name' => 'Alok Nath',
            'mobile' => '+91 9999988888',
            'delivery_address' => 'Varanasi Ghat Road',
            'hotel_or_dharamshala' => 'Ganga Lahari Hotel',
            'package_name' => '2 Litres Sacred Family Snan Pack',
            'water_quantity' => '2 Litres',
            'quantity_count' => 1,
            'unit_price' => 251.00,
            'total_price' => 251.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.water-collection-leads.update', $lead), [
            'status' => 'scheduled',
            'admin_notes' => 'Collection volunteer assigned: Ramesh (Haridwar Sewa). AWB #BLISS8899.',
            'pickup_date' => '2026-11-28',
        ]);

        $response->assertSessionHas('success');
        $lead->refresh();
        $this->assertEquals('scheduled', $lead->status);
        $this->assertEquals('Collection volunteer assigned: Ramesh (Haridwar Sewa). AWB #BLISS8899.', $lead->admin_notes);
        $this->assertEquals('2026-11-28', $lead->pickup_date->format('Y-m-d'));
    }

    public function test_admin_can_create_update_and_toggle_water_packages(): void
    {
        $admin = $this->createAdmin();

        // 1. Create a dynamic package tier
        $response = $this->actingAs($admin)->post(route('admin.water-collection-packages.store'), [
            'name' => '20 Litres Maha Kumbh Brass Temple Kalash',
            'quantity_litres' => 20.00,
            'unit' => 'Litres',
            'container_type' => 'Sacred Heavy Brass Vessel',
            'price' => 2100.00,
            'original_price' => 3500.00,
            'badge' => 'Temple Exclusive',
            'description' => 'Specially energized during Shahi Snan for temples.',
            'sort_order' => 5,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.water-collection-packages.index'));

        $package = WaterCollectionPackage::where('name', '20 Litres Maha Kumbh Brass Temple Kalash')->first();
        $this->assertNotNull($package);
        $this->assertEquals(2100.00, (float) $package->price);

        // 2. Toggle active status
        $toggleResponse = $this->actingAs($admin)->post(route('admin.water-collection-packages.toggle', $package));
        $toggleResponse->assertSessionHas('success');
        $package->refresh();
        $this->assertFalse($package->is_active);

        // 3. Update package
        $updateResponse = $this->actingAs($admin)->put(route('admin.water-collection-packages.update', $package), [
            'name' => '25 Litres Maha Kumbh Brass Temple Kalash',
            'quantity_litres' => 25.00,
            'unit' => 'Litres',
            'container_type' => 'Sacred Heavy Brass Vessel',
            'price' => 2500.00,
            'original_price' => 4000.00,
            'badge' => 'Mega Kumbh Special',
            'description' => 'Updated description.',
            'sort_order' => 6,
            'is_active' => 1,
        ]);

        $updateResponse->assertRedirect(route('admin.water-collection-packages.index'));
        $package->refresh();
        $this->assertEquals('25 Litres Maha Kumbh Brass Temple Kalash', $package->name);
        $this->assertEquals(2500.00, (float) $package->price);
        $this->assertTrue($package->is_active);
    }

    public function test_devotee_can_create_razorpay_order_for_water_collection(): void
    {
        WaterCollectionPackage::seedDefaultPackagesIfEmpty();
        $package = WaterCollectionPackage::first();

        $response = $this->postJson(route('water-collection.razorpay.order'), [
            'package_id' => $package->id,
            'quantity_count' => 2,
            'name' => 'Devotee Test',
            'email' => 'devotee@test.com',
            'mobile' => '+91 9998887776',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'order_id',
            'amount',
            'currency',
            'key',
            'name',
            'description',
            'prefill' => ['name', 'email', 'contact'],
        ]);
        $this->assertEquals(((float) $package->price * 2) * 100, $response->json('amount'));
    }

    public function test_devotee_can_submit_kumbh_lead_with_razorpay_payment(): void
    {
        WaterCollectionPackage::seedDefaultPackagesIfEmpty();
        $package = WaterCollectionPackage::first();

        $postData = [
            'name' => 'Amitabh Sharma',
            'mobile' => '+91 9876500001',
            'email' => 'amitabh@example.com',
            'delivery_address' => 'Villa 7, Green Glen, Outer Ring Road',
            'delivery_city' => 'Bengaluru',
            'delivery_state' => 'Karnataka',
            'delivery_pincode' => '560103',
            'hotel_or_dharamshala' => 'Haridwar Heritage Hotel',
            'package_id' => $package->id,
            'quantity_count' => 1,
            'payment_method' => 'razorpay',
            'razorpay_payment_id' => 'pay_test_kmb_123',
            'razorpay_order_id' => 'order_mock_test',
            'razorpay_signature' => 'mock_signature',
        ];

        $response = $this->post(route('water-collection.store'), $postData);

        $lead = WaterCollectionLead::where('mobile', '+91 9876500001')->first();
        $this->assertNotNull($lead);
        $this->assertEquals('razorpay', $lead->payment_method);
        $this->assertEquals('paid', $lead->payment_status);
        $this->assertEquals('pay_test_kmb_123', $lead->razorpay_payment_id);
        $response->assertRedirect(route('water-collection.confirmed', ['lead_number' => $lead->lead_number]));

        $confirmed = $this->get(route('water-collection.confirmed', ['lead_number' => $lead->lead_number]));
        $confirmed->assertStatus(200);
        $confirmed->assertSee('Paid Online via Razorpay');
        $confirmed->assertSee('pay_test_kmb_123');
    }

    public function test_devotee_can_submit_kumbh_lead_with_cod(): void
    {
        WaterCollectionPackage::seedDefaultPackagesIfEmpty();
        $package = WaterCollectionPackage::first();

        $postData = [
            'name' => 'Meena Gupta',
            'mobile' => '+91 9876500002',
            'delivery_address' => 'Plot 88, Civil Lines',
            'hotel_or_dharamshala' => 'Direct Haridwar Ghat Collection',
            'package_id' => $package->id,
            'quantity_count' => 1,
            'payment_method' => 'cod',
        ];

        $response = $this->post(route('water-collection.store'), $postData);

        $lead = WaterCollectionLead::where('mobile', '+91 9876500002')->first();
        $this->assertNotNull($lead);
        $this->assertEquals('cod', $lead->payment_method);
        $this->assertEquals('cod', $lead->payment_status);
        $response->assertRedirect(route('water-collection.confirmed', ['lead_number' => $lead->lead_number]));

        $confirmed = $this->get(route('water-collection.confirmed', ['lead_number' => $lead->lead_number]));
        $confirmed->assertStatus(200);
        $confirmed->assertSee('Pay on Collection (Cash / UPI at Pickup)');
    }

    public function test_online_puja_razorpay_order_and_booking_flow(): void
    {
        // 1. Create Razorpay order for online puja
        $orderResponse = $this->postJson(route('online-puja.razorpay.order'), [
            'package' => 'पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)',
            'devotee_name' => 'Sanjay Mishra',
            'phone' => '+91 9988776655',
            'email' => 'sanjay@example.com',
        ]);

        $orderResponse->assertStatus(200);
        $orderResponse->assertJsonStructure([
            'order_id',
            'amount',
            'currency',
            'key',
            'name',
            'description',
        ]);
        $this->assertEquals(510000, $orderResponse->json('amount')); // in paise

        // 2. Submit booking with Razorpay payment details
        $bookingResponse = $this->post(route('online-puja.book'), [
            'package' => 'पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)',
            'devotee_name' => 'Sanjay Mishra',
            'gotra' => 'Kashyap',
            'puja_date' => now()->addDays(5)->format('Y-m-d'),
            'phone' => '+91 9988776655',
            'email' => 'sanjay@example.com',
            'shipping_address' => 'House 55, Model Town, Delhi - 110009',
            'terms_agreed' => '1',
            'payment_method' => 'razorpay',
            'razorpay_payment_id' => 'pay_puja_test_999',
            'razorpay_order_id' => 'order_mock_puja',
            'razorpay_signature' => 'mock_signature',
        ]);

        $bookingResponse->assertSessionHas('booking_success');

        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Sanjay Mishra',
            'phone' => '+91 9988776655',
            'amount' => 5100.00,
            'payment_method' => 'razorpay',
            'payment_status' => 'paid',
            'razorpay_payment_id' => 'pay_puja_test_999',
        ]);
    }
}
