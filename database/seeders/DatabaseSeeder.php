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
        SiteSetting::set('free_shipping_threshold', '499', 'Free shipping on orders above INR 499');
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

        // 5. Batches for Traceability
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

        // 6. Categories (Inspired by 99Pandit with Parent & Child hierarchy)
        $categoriesData = [
            [
                'name' => 'Puja Essentials',
                'slug' => 'puja-essentials',
                'description' => 'Pure cotton batti, organic puja ghee, kalash, and sacred altar supplies.',
                'image' => 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=600&auto=format&fit=crop&q=80',
                'icon' => '🪔',
                'is_featured' => true,
                'sort_order' => 1,
                'children' => [
                    ['name' => 'Diya Batti & Wicks', 'slug' => 'diya-batti', 'sort_order' => 1],
                    ['name' => 'Puja Ghee & Camphor', 'slug' => 'puja-ghee', 'sort_order' => 2],
                    ['name' => 'Chandan & Tilak', 'slug' => 'chandan-tilak', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Hawan & Samagri',
                'slug' => 'hawan-samagri',
                'description' => 'Cow dung hawan cups, pure sambrani, loban, and authentic Vedic herbal mixes.',
                'image' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80',
                'icon' => '🔥',
                'is_featured' => true,
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Hawan Cups', 'slug' => 'hawan-cups', 'sort_order' => 1],
                    ['name' => 'Sambrani & Loban', 'slug' => 'sambrani-cup', 'sort_order' => 2],
                    ['name' => 'Organic Hawan Wood & Samagri', 'slug' => 'organic-samagri', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Sacred Gangajal',
                'slug' => 'sacred-gangajal',
                'description' => 'Untouched, hermetically sealed holy water from Brahmakund at Har Ki Pauri with batch traceability QR codes.',
                'image' => 'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=600&auto=format&fit=crop&q=80',
                'icon' => '🌊',
                'is_featured' => true,
                'sort_order' => 3,
                'children' => [
                    ['name' => 'Consecrated Bottles', 'slug' => 'gangajal-bottles', 'sort_order' => 1],
                    ['name' => 'Copper & Brass Urns', 'slug' => 'gangajal-urns', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Dhoop & Agarbatti',
                'slug' => 'dhoop-incense',
                'description' => 'Charcoal-free natural temple dhoop sticks and Himalayan floral incense.',
                'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&auto=format&fit=crop&q=80',
                'icon' => '🌸',
                'is_featured' => true,
                'sort_order' => 4,
                'children' => [
                    ['name' => 'Bamboo-Less Dhoop', 'slug' => 'bamboo-less-dhoop', 'sort_order' => 1],
                    ['name' => 'Sandalwood & Kasturi Cones', 'slug' => 'dhoop-cones', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Spiritual & Astro',
                'slug' => 'spiritual-items',
                'description' => 'Siddha Rudraksha, energised Karungali bracelets, Tulsi malas, and yantras.',
                'image' => 'https://images.unsplash.com/photo-1545235617-9465d2a55698?w=600&auto=format&fit=crop&q=80',
                'icon' => '📿',
                'is_featured' => true,
                'sort_order' => 5,
                'children' => [
                    ['name' => 'Rudraksha Beads', 'slug' => 'rudraksha', 'sort_order' => 1],
                    ['name' => 'Japa Malas & Kanthi', 'slug' => 'japa-malas', 'sort_order' => 2],
                    ['name' => 'Energized Yantras', 'slug' => 'yantras', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Festive & Puja Kits',
                'slug' => 'puja-kits',
                'description' => 'Comprehensive Vedic ritual boxes and festival-ready holy hampers.',
                'image' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01aa342?w=600&auto=format&fit=crop&q=80',
                'icon' => '🎁',
                'is_featured' => true,
                'sort_order' => 6,
                'children' => [
                    ['name' => 'Daily Worship Boxes', 'slug' => 'daily-kits', 'sort_order' => 1],
                    ['name' => 'Festival Special Kits', 'slug' => 'festive-combos', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Brass & Metal Ware',
                'slug' => 'brass-pooja-ware',
                'description' => 'Handcrafted pure brass Aarti diyas, temple bells, and panchapatra vessels.',
                'image' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=600&auto=format&fit=crop&q=80',
                'icon' => '🔔',
                'is_featured' => true,
                'sort_order' => 7,
                'children' => [
                    ['name' => 'Panchamukhi Diyas', 'slug' => 'brass-diyas', 'sort_order' => 1],
                    ['name' => 'Temple Bells & Plates', 'slug' => 'brass-bells', 'sort_order' => 2],
                ],
            ],
        ];

        $categoryMap = [];

        foreach ($categoriesData as $catItem) {
            $category = Category::updateOrCreate(
                ['slug' => $catItem['slug']],
                [
                    'name' => $catItem['name'],
                    'description' => $catItem['description'],
                    'image' => $catItem['image'],
                    'icon' => $catItem['icon'],
                    'is_featured' => $catItem['is_featured'],
                    'sort_order' => $catItem['sort_order'],
                    'is_active' => true,
                ]
            );

            $categoryMap[$catItem['slug']] = $category;

            if (! empty($catItem['children'])) {
                foreach ($catItem['children'] as $child) {
                    Category::updateOrCreate(
                        ['slug' => $child['slug']],
                        [
                            'parent_id' => $category->id,
                            'name' => $child['name'],
                            'sort_order' => $child['sort_order'],
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        // 7. Rich Catalog of Products (Inspired by 99Pandit E-Commerce)
        $products = [
            // PUJA ESSENTIALS
            [
                'category_slug' => 'puja-essentials',
                'name' => 'Premium & Long Lasting Cotton Wicks - White (1100 Pieces)',
                'slug' => 'cotton-wicks-white-1100-pieces',
                'short_description' => 'Slow-burning, pure surgical cotton wicks for daily Akhand Diya and morning Aarti.',
                'description' => 'Hand-spun from 100% natural organic cotton, these pristine white battis burn steadily for hours without producing sooty residue. Designed specially for brass, clay, and silver diyas.',
                'price' => 159.00,
                'compare_price' => 259.00,
                'member_price' => 129.00,
                'rating' => 4.85,
                'reviews_count' => 64,
                'badge' => '38% OFF',
                'is_bestseller' => true,
                'is_featured' => true,
                'stock' => 350,
                'sku' => 'HB-WICK-WH-1100',
                'image' => 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Count' => '1100 Wicks', 'Material' => '100% Pure Organic Cotton', 'Burn Duration' => 'Long Lasting'],
            ],
            [
                'category_slug' => 'puja-essentials',
                'name' => 'Long Lasting Chandan Scented Round Cotton Batti (500 Pieces)',
                'slug' => 'cotton-wicks-chandan-500-pieces',
                'short_description' => 'Infused with natural sandalwood essence for fragrant and blissful evening prayers.',
                'description' => 'Infused with sacred Chandan essential oil, these round battis fill your pooja room with an uplifting temple aroma as the flame burns.',
                'price' => 199.00,
                'compare_price' => 399.00,
                'member_price' => 159.00,
                'rating' => 4.90,
                'reviews_count' => 88,
                'badge' => '50% OFF',
                'is_bestseller' => true,
                'is_featured' => true,
                'stock' => 280,
                'sku' => 'HB-WICK-CH-500',
                'image' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Count' => '500 Wicks', 'Aroma' => 'Natural Sandalwood', 'Shape' => 'Round Phool Batti'],
            ],
            [
                'category_slug' => 'puja-essentials',
                'name' => 'Pure Desi Cow Ghee for Diya & Hawan (500ml Glass Jar)',
                'slug' => 'pure-desi-cow-ghee-500ml',
                'short_description' => 'Traditional Bilona cow ghee crafted exclusively for auspicious Aarti and holy Hawans.',
                'description' => 'Clean, unadulterated cow ghee prepared with reverence. Enhances spiritual aura, generates pure sattvic vibrations, and emits zero toxic smoke.',
                'price' => 389.00,
                'compare_price' => 549.00,
                'member_price' => 329.00,
                'rating' => 4.95,
                'reviews_count' => 112,
                'badge' => 'Pure Vedic',
                'is_bestseller' => true,
                'is_featured' => true,
                'stock' => 150,
                'sku' => 'HB-GHEE-500',
                'image' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Volume' => '500 ml', 'Source' => 'Desi Cow Bilona Method', 'Container' => 'Food Grade Glass Jar'],
            ],

            // HAWAN & SAMAGRI
            [
                'category_slug' => 'hawan-samagri',
                'name' => 'Premium Cow Dung Hawan Cups with Guggal & Loban (Pack of 12)',
                'slug' => 'cow-dung-hawan-cups-pack-12',
                'short_description' => 'Ready-to-use mini hawan cups filled with natural herbs, camphor, and temple loban.',
                'description' => 'Experience the sacred purifying energy of an authentic Vedic Hawan in just 15 minutes. Made with indigenous desi cow dung base and packed with fragrant Guggal, Loban, and Kapur.',
                'price' => 249.00,
                'compare_price' => 399.00,
                'member_price' => 199.00,
                'rating' => 4.92,
                'reviews_count' => 143,
                'badge' => 'Bestseller',
                'is_bestseller' => true,
                'is_featured' => true,
                'stock' => 500,
                'sku' => 'HB-HWN-CUP-12',
                'image' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Pack Size' => '12 Cups + 1 Fibre Stand', 'Burn Time' => '20-25 mins', 'Ingredients' => 'Desi Cow Dung, Guggal, Loban, Herbs'],
            ],
            [
                'category_slug' => 'hawan-samagri',
                'name' => 'Organic Himalayan Herbal Hawan Samagri (500g Resealable Pouch)',
                'slug' => 'organic-himalayan-hawan-samagri-500g',
                'short_description' => '51 Sacred Vedic herbs including Nagarmotha, Jatamansi, and dry flower petals.',
                'description' => 'Formulated strictly in adherence with Vedic shastras. Free of artificial sawdust or synthetic scents. Produces divine sacred smoke that cleanses domestic atmosphere.',
                'price' => 199.00,
                'compare_price' => 299.00,
                'member_price' => 160.00,
                'rating' => 4.80,
                'reviews_count' => 52,
                'badge' => '33% OFF',
                'is_bestseller' => false,
                'is_featured' => true,
                'stock' => 200,
                'sku' => 'HB-HWN-SMG-500',
                'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Weight' => '500 grams', 'Herb Count' => '51 Herbs', 'Packaging' => 'Zip Lock Moisture-Proof Pouch'],
            ],
            [
                'category_slug' => 'hawan-samagri',
                'name' => 'Pure Charcoal-Free Sambrani Dhoop Cups (Pack of 24 with Brass Stand)',
                'slug' => 'sambrani-dhoop-cups-24',
                'short_description' => 'Soothing benzoin resin cups for peaceful morning pooja and negative energy warding.',
                'description' => 'Handcrafted using traditional temple formulas. Spreads dense, fragrant, and spiritually elevating smoke across the household without eye irritation.',
                'price' => 349.00,
                'compare_price' => 499.00,
                'member_price' => 289.00,
                'rating' => 4.88,
                'reviews_count' => 76,
                'badge' => '30% OFF',
                'is_bestseller' => false,
                'is_featured' => true,
                'stock' => 190,
                'sku' => 'HB-SMB-CUP-24',
                'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Pack' => '24 Cups', 'Free Gift' => 'Mini Brass Cup Holder', 'Fragrance' => 'Pure Temple Sambrani'],
            ],

            // SACRED GANGAJAL (USP Products)
            [
                'category_slug' => 'sacred-gangajal',
                'name' => 'Pure Brahmakund Gangajal (500ml Sacred Hermetic Bottle)',
                'slug' => 'pure-brahmakund-gangajal-500ml',
                'short_description' => '100% untouched holy water from Har Ki Pauri with batch QR verification.',
                'description' => "Experience the divine sanctity of Maa Ganga at home. Sourced straight from the sacred Brahmakund at Har Ki Pauri during the auspicious Brahma Muhurta morning Aarti.\n\nIncluded FREE every month for 5-Year Haridwar Bliss members!",
                'price' => 149.00,
                'compare_price' => 199.00,
                'member_price' => 0.00, // 1st bottle free for members!
                'rating' => 5.00,
                'reviews_count' => 312,
                'badge' => 'Devotee Favorite',
                'is_gangajal' => true,
                'batch_id' => $batch1->id,
                'volume_ml' => 500,
                'stock' => 500,
                'sku' => 'HB-GJ-500',
                'image' => 'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=600&auto=format&fit=crop&q=80',
                'purity_details' => 'Sourced from Brahmakund, Haridwar. Lab Tested: pH 7.8, Zero microbial contamination, rich in natural Himalayan minerals.',
                'specifications' => ['Origin' => 'Brahmakund, Haridwar', 'Volume' => '500 ml', 'Packaging' => 'Hermetically Sealed Glass Container'],
                'is_bestseller' => true,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'sacred-gangajal',
                'name' => 'Pure Brahmakund Gangajal 1 Litre — Handcrafted Copper Kalash Edition',
                'slug' => 'pure-gangajal-1l-copper-cask',
                'short_description' => 'Sacred holy Ganga water delivered inside a consecrated pure copper puja kalash.',
                'description' => 'Combining the supreme spiritual holiness of Brahmakund Gangajal with the Ayurvedic and therapeutic virtues of pure Tamra (copper). Perfect for your home mandir and Shiva Abhishek.',
                'price' => 399.00,
                'compare_price' => 599.00,
                'member_price' => 299.00,
                'rating' => 4.96,
                'reviews_count' => 95,
                'badge' => 'Copper Sanctum',
                'is_gangajal' => true,
                'batch_id' => $batch2->id,
                'volume_ml' => 1000,
                'stock' => 120,
                'sku' => 'HB-GJ-1000-CU',
                'image' => 'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=600&auto=format&fit=crop&q=80',
                'purity_details' => '100% authentic Brahmakund water enriched inside an engraved pure copper vessel.',
                'specifications' => ['Volume' => '1000 ml', 'Vessel' => '100% Pure Embossed Copper', 'Batch Traceability' => 'Verified on QR Portal'],
                'is_bestseller' => true,
                'is_featured' => true,
            ],

            // DHOOP & AGARBATTI
            [
                'category_slug' => 'dhoop-incense',
                'name' => 'Natural Chandan & Kesar Temple Dhoop Sticks (Bamboo-Free, 50 Sticks)',
                'slug' => 'ganga-blessings-pure-kasturi-dhoop-sticks',
                'short_description' => 'Charcoal-free sacred dhoop sticks made with temple flowers and pure sandalwood.',
                'description' => 'Slow-burning, soothing aromatic dhoop prepared using naturally upcycled Ganga Aarti flowers, Himalayan herbs, and natural essential oils. 100% bamboo-free and non-toxic.',
                'price' => 149.00,
                'compare_price' => 220.00,
                'member_price' => 119.00,
                'rating' => 4.84,
                'reviews_count' => 78,
                'badge' => '32% OFF',
                'is_bestseller' => false,
                'is_featured' => true,
                'stock' => 300,
                'sku' => 'HB-DH-CH-50',
                'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Sticks' => '50 Sticks', 'Burn Duration' => '45 mins each', 'Charcoal' => '0% Charcoal'],
            ],
            [
                'category_slug' => 'dhoop-incense',
                'name' => 'Kasturi & Guggal Organic Incense Cones with Ceramic Stand',
                'slug' => 'kasturi-guggal-incense-cones',
                'short_description' => 'High resonance aroma cones for meditation, vastu dosha peace, and divine altar fragrance.',
                'description' => 'Blended with natural tree resins, dry herbs, and sacred Gangajal. Long lingering fragrance that uplifts mood and dispels lethargy.',
                'price' => 169.00,
                'compare_price' => 249.00,
                'member_price' => 139.00,
                'rating' => 4.79,
                'reviews_count' => 44,
                'badge' => 'Aromatherapy',
                'is_bestseller' => false,
                'is_featured' => false,
                'stock' => 220,
                'sku' => 'HB-DH-CONE-30',
                'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Count' => '30 Cones', 'Holder' => 'Ceramic Base Included', 'Fragrance' => 'Pure Kasturi & Guggal'],
            ],

            // SPIRITUAL & ASTRO
            [
                'category_slug' => 'spiritual-items',
                'name' => 'Consecrated 5-Mukhi Indonesian Rudraksha Japa Mala (108+1 Beads)',
                'slug' => 'consecrated-5-mukhi-rudraksha-mala',
                'short_description' => 'Purified in Brahmakund Gangajal at Har Ki Pauri with red knotted tassel.',
                'description' => 'Authentic five-faced Rudraksha beads strung in traditional knotted thread. Blessed at the holy ghats of Haridwar for clarity of mind, cardiovascular wellness, and Japa meditation.',
                'price' => 499.00,
                'compare_price' => 899.00,
                'member_price' => 399.00,
                'rating' => 4.93,
                'reviews_count' => 168,
                'badge' => '44% OFF',
                'is_bestseller' => true,
                'is_featured' => true,
                'stock' => 140,
                'sku' => 'HB-RD-MALA-108',
                'image' => 'https://images.unsplash.com/photo-1545235617-9465d2a55698?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Bead Count' => '108 + 1 Guru Bead', 'Mukhi' => '5 Mukhi (Pancha Mukhi)', 'Purification' => 'Ganga Snan Sanctified'],
            ],
            [
                'category_slug' => 'spiritual-items',
                'name' => 'Pure Karungali Wood Energy Bead Bracelet (8mm Stretchable)',
                'slug' => 'karungali-wood-energy-bracelet',
                'short_description' => 'Genuine black ebony wood bracelet for grounding, protection from nazar, and aura strength.',
                'description' => 'Carved from sacred Karungali (Ebony) tree heartwood. Traditionally worn by sadhus and yogis to balance planetary influences and absorb electromagnetic stress.',
                'price' => 349.00,
                'compare_price' => 599.00,
                'member_price' => 279.00,
                'rating' => 4.82,
                'reviews_count' => 91,
                'badge' => 'Astro Shield',
                'is_bestseller' => false,
                'is_featured' => true,
                'stock' => 180,
                'sku' => 'HB-KARUNGALI-BRC',
                'image' => 'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Bead Size' => '8mm', 'Material' => 'Pure Karungali Ebony Wood', 'Size' => 'Stretchable Universal Fit'],
            ],
            [
                'category_slug' => 'spiritual-items',
                'name' => 'Shree Yantra Sacred Brass Plate (3x3 inch, Precision Etched)',
                'slug' => 'shree-yantra-brass-plate-3x3',
                'short_description' => 'Sacred geometric cosmic diagram for attracting prosperity, peace, and abundance.',
                'description' => 'Meticulously engraved on pure virgin brass sheet. Ideal for keeping in cash boxes, home puja altars, office desks, and entrance thresholds.',
                'price' => 299.00,
                'compare_price' => 499.00,
                'member_price' => 229.00,
                'rating' => 4.90,
                'reviews_count' => 54,
                'badge' => 'Vedic Vastu',
                'is_bestseller' => false,
                'is_featured' => true,
                'stock' => 110,
                'sku' => 'HB-YNT-SHREE',
                'image' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Dimensions' => '3 inch x 3 inch', 'Metal' => 'Pure Brass Plate', 'Finish' => 'Gold Lacquered'],
            ],

            // FESTIVE & PUJA KITS
            [
                'category_slug' => 'puja-kits',
                'name' => 'Complete Daily Griha Puja & Sandhya Aarti Box (18 Sacred Essentials)',
                'slug' => 'complete-daily-griha-puja-kit',
                'short_description' => 'All-in-one divine worship kit containing batti, ghee, Gangajal, chandan, dhoop, and roli.',
                'description' => 'Takes away the hassle of finding separate puja ingredients. Contains certified pure Brahmakund Gangajal, desi cow ghee, kumkum, chandan, akshat, cotton wicks, dhoop cups, and camphor.',
                'price' => 599.00,
                'compare_price' => 999.00,
                'member_price' => 499.00,
                'rating' => 4.97,
                'reviews_count' => 189,
                'badge' => 'Combo Saver 40%',
                'is_bestseller' => true,
                'is_featured' => true,
                'stock' => 120,
                'sku' => 'HB-KIT-DAILY-18',
                'image' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01aa342?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Items Included' => '18 Divine Articles', 'Duration' => 'Sufficient for 30 Days', 'Packaging' => 'Ornamental Gift Box'],
            ],
            [
                'category_slug' => 'puja-kits',
                'name' => 'Maha Shivaratri & Rudra Abhishek Special Kit with Sacred Bilva Patra',
                'slug' => 'maha-shivaratri-rudra-abhishek-kit',
                'short_description' => 'Dedicated kit for Shiva Abhishek with Gangajal, Bhasma, Sandalwood, and Panchamrut cups.',
                'description' => 'Prepared in consultation with senior Har Ki Pauri temple priests. Contains holy water straight from Brahmakund, pure Vibhuti bhasma, organic bilva powder, and honey.',
                'price' => 699.00,
                'compare_price' => 1199.00,
                'member_price' => 549.00,
                'rating' => 5.00,
                'reviews_count' => 73,
                'badge' => 'Mahadev Special',
                'is_bestseller' => false,
                'is_featured' => true,
                'stock' => 85,
                'sku' => 'HB-KIT-SHIVA',
                'image' => 'https://images.unsplash.com/photo-1582738411706-bfc8e691d1c2?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Occasion' => 'Shiva Abhishek & Pradosh Vrat', 'Items' => '12 Sanctified Items', 'Holy Water' => 'Included 500ml Gangajal'],
            ],

            // BRASS & METAL WARE
            [
                'category_slug' => 'brass-pooja-ware',
                'name' => 'Handcrafted Brass Panchamukhi Aarti Diya with Peacock Handle',
                'slug' => 'handcrafted-panchamukhi-brass-aarti-diya',
                'short_description' => 'Solid brass five-faced Aarti lamp crafted after Har Ki Pauri priests’ lamps.',
                'description' => 'Cast in solid pure virgin brass with a heavy ergonomic handle. Designed for performing the traditional five-element Aarti worship during morning and evening prayers.',
                'price' => 499.00,
                'compare_price' => 749.00,
                'member_price' => 379.00,
                'rating' => 4.91,
                'reviews_count' => 124,
                'badge' => 'Pure Brass',
                'is_bestseller' => true,
                'is_featured' => true,
                'stock' => 80,
                'sku' => 'HB-BR-DIYA-5',
                'image' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Material' => '100% Pure Virgin Brass', 'Weight' => '420 grams', 'Capacity' => '5 Wicks'],
            ],
            [
                'category_slug' => 'brass-pooja-ware',
                'name' => 'Traditional Brass Temple Ghanti (Bell) with Nandi Crest (4.5 Inch)',
                'slug' => 'brass-temple-bell-nandi',
                'short_description' => 'Clear high resonance acoustic frequency bell for dispelling negative vibrations.',
                'description' => 'Hand-cast brass bell topped with the auspicious seated Nandi silhouette. Produces a sustained melodious reverberation that calms the mind during pooja.',
                'price' => 349.00,
                'compare_price' => 499.00,
                'member_price' => 289.00,
                'rating' => 4.86,
                'reviews_count' => 61,
                'badge' => 'Acoustic Sound',
                'is_bestseller' => false,
                'is_featured' => true,
                'stock' => 105,
                'sku' => 'HB-BR-BELL-NANDI',
                'image' => 'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=600&auto=format&fit=crop&q=80',
                'specifications' => ['Height' => '4.5 inches', 'Weight' => '280 grams', 'Metal' => 'Pure Bell-Metal Brass'],
            ],
        ];

        foreach ($products as $pData) {
            $catSlug = $pData['category_slug'];
            unset($pData['category_slug']);

            if (isset($categoryMap[$catSlug])) {
                $pData['category_id'] = $categoryMap[$catSlug]->id;
            }

            $pData['is_active'] = true;

            Product::updateOrCreate(
                ['slug' => $pData['slug']],
                $pData
            );
        }

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

        $gangajalProd = Product::where('is_gangajal', true)->first();
        if ($gangajalProd) {
            OrderItem::firstOrCreate(
                ['order_id' => $sampleOrder->id, 'product_id' => $gangajalProd->id],
                [
                    'product_name' => $gangajalProd->name,
                    'unit_price' => 149.00,
                    'quantity' => 1,
                    'total_price' => 0.00,
                    'is_free_monthly_bottle' => true,
                ]
            );
        }
    }
}
