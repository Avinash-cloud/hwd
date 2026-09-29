<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Membership;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Site Settings
        SiteSetting::set('membership_fee', '500', 'One-time 5-year membership fee in INR');
        SiteSetting::set('membership_validity_years', '5', 'Validity duration in years');
        SiteSetting::set('shipping_charge', '149', 'Flat shipping rate in INR per order');
        SiteSetting::set('free_first_bottle_enabled', '1', 'Enable free monthly 1st Gangajal bottle for active members');
        SiteSetting::set('support_phone', '+91 98765 43210', 'Customer care phone');
        SiteSetting::set('support_email', 'bliss@haridwarbliss.com', 'Customer care email');
        SiteSetting::set('ghat_location_default', 'Har Ki Pauri, Brahmakund, Haridwar, Uttarakhand', 'Default holy collection ghat');

        // 2. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@haridwarbliss.com'],
            [
                'name' => 'Admin Haridwar Bliss',
                'phone' => '+91 9876543210',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $member = User::firstOrCreate(
            ['email' => 'member@haridwarbliss.com'],
            [
                'name' => 'Ramesh Sharma',
                'phone' => '+91 9811122233',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        $guestUser = User::firstOrCreate(
            ['email' => 'guest@haridwarbliss.com'],
            [
                'name' => 'Priya Patel',
                'phone' => '+91 9822233344',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // 3. Active 5-Year Membership for $member
        Membership::firstOrCreate(
            ['user_id' => $member->id],
            [
                'status' => 'active',
                'fee_paid' => 500.00,
                'validity_years' => 5,
                'starts_at' => now(),
                'expires_at' => now()->addYears(5),
                'payment_method' => 'razorpay',
                'payment_id' => 'pay_MOCK'.Str::random(10),
                'terms_accepted' => true,
            ]
        );

        // 4. Default Shipping Address for $member
        $address = Address::firstOrCreate(
            ['user_id' => $member->id, 'is_default' => true],
            [
                'type' => 'shipping',
                'recipient_name' => 'Ramesh Sharma',
                'phone' => '+91 9811122233',
                'address_line1' => 'Flat 402, Shanti Niketan Apts',
                'address_line2' => 'Civil Lines Road',
                'landmark' => 'Near Hanuman Temple',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'postal_code' => '110054',
                'country' => 'India',
            ]
        );

        // 5. Categories
        $catGangajal = Category::firstOrCreate(
            ['slug' => 'sacred-gangajal'],
            [
                'name' => 'Sacred Gangajal',
                'description' => 'Pure and untouched holy water directly collected from Brahmakund at Har Ki Pauri, Haridwar.',
                'image' => '/images/categories/gangajal.jpg',
                'sort_order' => 1,
            ]
        );

        $catBrass = Category::firstOrCreate(
            ['slug' => 'brass-pooja-ware'],
            [
                'name' => 'Brass Pooja Ware',
                'description' => 'Handcrafted pure brass kalash, aarti diyas, and sacred vessels blessed at Haridwar.',
                'image' => '/images/categories/brass.jpg',
                'sort_order' => 2,
            ]
        );

        $catDhoop = Category::firstOrCreate(
            ['slug' => 'dhoop-incense'],
            [
                'name' => 'Vedic Dhoop & Incense',
                'description' => 'Natural havan samagri, cow dung dhoop cups, and Himalayan herbal agarbatti.',
                'image' => '/images/categories/dhoop.jpg',
                'sort_order' => 3,
            ]
        );

        $catGifts = Category::firstOrCreate(
            ['slug' => 'divine-gifts-prasad'],
            [
                'name' => 'Devotional Souvenirs & Prasad',
                'description' => 'Rudraksha beads, Haridwar temple souvenirs, and sacred puja kits.',
                'image' => '/images/categories/gifts.jpg',
                'sort_order' => 4,
            ]
        );

        // 6. Batches for Traceability
        $batch1 = Batch::firstOrCreate(
            ['batch_number' => 'HB-GANG-2026-001'],
            [
                'sourcing_ghat' => 'Har Ki Pauri, Brahmakund, Haridwar, Uttarakhand',
                'collection_date' => now()->subDays(15),
                'packaging_date' => now()->subDays(13),
                'description' => 'Sacred morning collection during Brahma Muhurta Ganga Aarti under continuous Vedic chanting.',
                'purity_notes' => 'Laboratory Tested Purity: pH Level 7.8, Dissolved Oxygen 9.2 mg/L, Zero Bacterial Pathogens, Untouched Bottling.',
                'lab_certificate_path' => '/certificates/hb-cert-2026-001.pdf',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'status' => 'ready',
            ]
        );

        $batch2 = Batch::firstOrCreate(
            ['batch_number' => 'HB-GANG-2026-002'],
            [
                'sourcing_ghat' => 'Har Ki Pauri, Brahmakund, Haridwar, Uttarakhand',
                'collection_date' => now()->subDays(5),
                'packaging_date' => now()->subDays(3),
                'description' => 'Evening Ganga Aarti auspicious batch collected during Sandhya Deepotsav.',
                'purity_notes' => 'Laboratory Tested Purity: pH Level 7.9, Naturally Filtered Himalayan Mineral Water, Hermetically Sealed.',
                'lab_certificate_path' => '/certificates/hb-cert-2026-002.pdf',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'status' => 'ready',
            ]
        );

        // 7. Products
        $p1 = Product::firstOrCreate(
            ['slug' => 'pure-brahmakund-gangajal-500ml'],
            [
                'category_id' => $catGangajal->id,
                'batch_id' => $batch1->id,
                'name' => 'Pure Brahmakund Gangajal (500ml Sacred Bottle)',
                'short_description' => '100% pure holy water from Har Ki Pauri. Untouched, hermetically sealed, lab certified.',
                'description' => "Experience the divine sanctity of Maa Ganga in your home. Sourced straight from the sacred Brahmakund at Har Ki Pauri during the auspicious Brahma Muhurta, this 500ml bottle is bottled using untouched sanitary techniques while preserving its natural vitality, minerals, and spiritual potency.\n\nIncluded as part of your monthly Haridwar Bliss membership delivery.",
                'price' => 149.00,
                'member_price' => 0.00, // 1st bottle free for members!
                'is_gangajal' => true,
                'volume_ml' => 500,
                'stock' => 500,
                'sku' => 'HB-GJ-500',
                'image' => '/images/products/gangajal-500ml.png',
                'purity_details' => 'Sourced from Brahmakund, Haridwar. Lab Tested: pH 7.8, Zero microbial contamination, rich in natural Himalayan minerals.',
                'specifications' => [
                    'Origin' => 'Brahmakund, Haridwar',
                    'Volume' => '500 ml',
                    'Bottle Material' => 'UV-Protected Food-Grade Container with Hermetic Seal',
                    'Shelf Life' => 'Indefinite (does not spoil as per Vedic & scientific analysis)',
                ],
                'benefits' => 'Essential for daily home worship, Abhishek of Shivling, Purifying domestic energies, Pitru Tarpan, and sacred Sanskars.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        $p2 = Product::firstOrCreate(
            ['slug' => 'pure-gangajal-1l-copper-cask'],
            [
                'category_id' => $catGangajal->id,
                'batch_id' => $batch1->id,
                'name' => 'Sacred Gangajal 1 Litre — Copper Vessel Edition',
                'short_description' => 'Pure Brahmakund Gangajal delivered inside an auspicious handcrafted pure copper kalash.',
                'description' => 'Combining the holiness of Gangajal with the Ayurvedic and spiritual virtues of pure copper (Tamra). Perfect for temple sanctums and enduring pooja altar placement.',
                'price' => 399.00,
                'member_price' => 249.00,
                'is_gangajal' => true,
                'volume_ml' => 1000,
                'stock' => 150,
                'sku' => 'HB-GJ-1000-CU',
                'image' => '/images/products/gangajal-copper.png',
                'purity_details' => '100% natural Brahmakund holy water enriched in consecrated copper vessel.',
                'specifications' => [
                    'Origin' => 'Har Ki Pauri, Haridwar',
                    'Volume' => '1000 ml',
                    'Vessel Material' => 'Pure Hand-Engraved Copper',
                ],
                'benefits' => 'Ideal for Abhishek, Ganga Aarti at home, and auspicious gifting during weddings and housewarmings.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        $p3 = Product::firstOrCreate(
            ['slug' => 'handcrafted-panchamukhi-brass-aarti-diya'],
            [
                'category_id' => $catBrass->id,
                'name' => 'Handcrafted Brass Panchamukhi Aarti Diya',
                'short_description' => 'Authentic five-faced brass Aarti lamp designed after the sacred priests’ lamps of Haridwar.',
                'description' => 'Cast in solid pure brass with ornamental peacock handle, designed for performing the traditional five-element Aarti worship.',
                'price' => 499.00,
                'member_price' => 379.00,
                'is_gangajal' => false,
                'stock' => 80,
                'sku' => 'HB-BR-DIYA-5',
                'image' => '/images/products/brass-diya.png',
                'purity_details' => 'Pure virgin brass with mirror finish, consecrated at Haridwar.',
                'specifications' => [
                    'Material' => '100% Pure Brass',
                    'Weight' => '420 grams',
                    'Capacity' => '5 wicks / cotton batti',
                ],
                'benefits' => 'Fills home with positive vibrations and replicates the divine aura of the Har Ki Pauri evening Aarti.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        $p4 = Product::firstOrCreate(
            ['slug' => 'ganga-blessings-pure-kasturi-dhoop-sticks'],
            [
                'category_id' => $catDhoop->id,
                'name' => 'Haridwar Kasturi & Chandan Temple Dhoop',
                'short_description' => 'Charcoal-free sacred dhoop sticks made with temple flowers and pure sandalwood.',
                'description' => 'Slow-burning, soothing aromatic dhoop prepared using naturally upcycled Ganga Aarti flowers, Himalayan herbs, and natural essential oils.',
                'price' => 220.00,
                'member_price' => 170.00,
                'is_gangajal' => false,
                'stock' => 300,
                'sku' => 'HB-DH-KAST',
                'image' => '/images/products/dhoop.png',
                'purity_details' => '0% Charcoal, 0% Synthetic Fragrance. 100% natural flower pulp.',
                'specifications' => [
                    'Quantity' => 'Pack of 50 sticks',
                    'Burn Time' => '45 minutes per stick',
                ],
                'benefits' => 'Cleanses household air, enhances meditation focus, and induces divine serenity.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        $p5 = Product::firstOrCreate(
            ['slug' => 'consecrated-5-mukhi-rudraksha-mala'],
            [
                'category_id' => $catGifts->id,
                'name' => 'Consecrated 5-Mukhi Rudraksha Mala (108+1 Beads)',
                'short_description' => 'Sourced from Uttarakhand hills, purified in Gangajal at Brahmakund.',
                'description' => 'Authentic five-faced Rudraksha beads strung in traditional knotted red thread, dipped and sanctified in Har Ki Pauri Brahmakund Gangajal.',
                'price' => 599.00,
                'member_price' => 450.00,
                'is_gangajal' => false,
                'stock' => 120,
                'sku' => 'HB-RD-MALA',
                'image' => '/images/products/rudraksha.png',
                'purity_details' => 'Natural tested rudraksha seeds with distinct natural grooves.',
                'specifications' => [
                    'Count' => '108 + 1 Guru Bead',
                    'Origin' => 'Uttarakhand / Garhwal',
                ],
                'benefits' => 'Spiritual protection, mental peace, blood pressure regulation, and mantra chanting.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        // 8. Sample Dispatched Order for $member (with Traceability QR)
        $sampleOrder = Order::firstOrCreate(
            ['order_number' => 'HB-ORD-2026-1001'],
            [
                'user_id' => $member->id,
                'batch_id' => $batch1->id,
                'subtotal' => 149.00,
                'discount_amount' => 149.00, // 1st bottle free
                'shipping_amount' => 149.00,
                'total_amount' => 149.00,
                'payment_method' => 'razorpay',
                'payment_status' => 'paid',
                'payment_id' => 'pay_MOCK_ORD_1001',
                'order_status' => 'dispatched',
                'tracking_number' => 'SR-IND-778899',
                'courier_name' => 'Shiprocket Express',
                'qr_token' => 'HB-VERIFY-2026-SAMPLE',
                'shipping_address' => [
                    'recipient_name' => 'Ramesh Sharma',
                    'phone' => '+91 9811122233',
                    'address_line1' => 'Flat 402, Shanti Niketan Apts',
                    'address_line2' => 'Civil Lines Road',
                    'city' => 'Delhi',
                    'state' => 'Delhi',
                    'postal_code' => '110054',
                    'country' => 'India',
                ],
                'dispatched_at' => now()->subDay(),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $sampleOrder->id, 'product_id' => $p1->id],
            [
                'product_name' => $p1->name,
                'unit_price' => 149.00,
                'quantity' => 1,
                'total_price' => 0.00,
                'is_free_monthly_bottle' => true,
            ]
        );
    }
}
