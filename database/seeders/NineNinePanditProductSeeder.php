<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NineNinePanditProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Simulates admin data entry for products curated from shop.99pandit.com
     * using the exact store logic of Admin\ProductController.
     */
    public function run(): void
    {
        $batch = Batch::first();

        // Ensure category mappings exist (handling root and subcategory slugs)
        $categoryMap = [
            'diya-batti' => Category::where('slug', 'diya-batti')->value('id')
                ?? Category::where('slug', 'puja-essentials')->value('id'),
            'puja-ghee' => Category::where('slug', 'puja-ghee')->value('id')
                ?? Category::where('slug', 'puja-essentials')->value('id'),
            'hawan-cups' => Category::where('slug', 'hawan-cups')->value('id')
                ?? Category::where('slug', 'hawan-samagri')->value('id'),
            'chandan-tilak' => Category::where('slug', 'chandan-tilak')->value('id')
                ?? Category::where('slug', 'puja-essentials')->value('id'),
            'yantras' => Category::where('slug', 'yantras')->value('id')
                ?? Category::where('slug', 'spiritual-items')->value('id'),
            'rudraksha' => Category::where('slug', 'rudraksha')->value('id')
                ?? Category::where('slug', 'spiritual-items')->value('id'),
            'brass-diyas' => Category::where('slug', 'brass-diyas')->value('id')
                ?? Category::where('slug', 'brass-pooja-ware')->value('id'),
            'daily-kits' => Category::where('slug', 'daily-kits')->value('id')
                ?? Category::where('slug', 'puja-kits')->value('id'),
        ];

        // 8 Realistic Products inspired by shop.99pandit.com
        $adminProductsPayload = [
            // 1. Ready-to-Use Pure Cow Ghee Diya Batti (Bestseller on 99pandit)
            [
                'category_key' => 'diya-batti',
                'name' => 'Ready-to-Light Pure Cow Ghee Diya Batti (Box of 100 Wicks)',
                'short_description' => 'Wax-free pure cow ghee soaked cotton battis that burn steadily for 30+ minutes with no messy oil pouring.',
                'description' => "Experience hassle-free and peaceful daily Aarti. Handcrafted using authentic Desi Cow Ghee infused with organic camphor and subtle sandalwood essence.\n\nEach pre-formed ghee wick is ready to be placed directly into brass or clay diyas without needing liquid ghee or oil. Leaves zero carbon residue and emits a divine temple fragrance.",
                'price' => 249.00,
                'compare_price' => 399.00,
                'member_price' => 199.00,
                'stock' => 250,
                'sku' => '99P-GHEE-WCK-100',
                'badge' => '99Pandit Bestseller',
                'rating' => 4.90,
                'reviews_count' => 118,
                'is_gangajal' => false,
                'is_member_free_eligible' => false,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'purity_details' => 'Formulated with 100% pure Desi Cow Ghee and surgical organic cotton. Absolutely free from paraffin wax, animal fat, or synthetic petroleum binders.',
                'benefits' => 'Ignites instantly with a single matchstick. Steady, smokeless golden flame that purifies the living room aura and elevates concentration during chanting.',
                'specifications' => [
                    'Quantity' => '100 Ready-to-Light Wicks',
                    'Burn Duration' => '30 - 35 Minutes per wick',
                    'Ingredients' => 'Pure Desi Cow Ghee, Natural Camphor, Organic Cotton',
                    'Container' => 'Food-grade airtight moisture-proof box',
                ],
                'image_url' => '/images/products/ghee-diya-batti.jpg',
                'gallery' => [
                    '/images/products/ghee-diya-batti.jpg',
                    '/images/products/akhand-jyoti-diya.jpg',
                ],
            ],

            // 2. Original Bhimseni Camphor (Pure Kapoor)
            [
                'category_key' => 'puja-ghee',
                'name' => 'Original Pure Bhimseni Camphor (Karpur Flakes - 200g Airtight Jar)',
                'short_description' => '100% natural crystalline Bhimseni Kapoor that completely sublimates with zero black smoke and leaves zero ash.',
                'description' => "Distilled directly from natural pine resin extracts according to ancient Ayurvedic shastras. Unlike machine-pressed round synthetic camphor tablets, original Bhimseni kapur has an organic irregular crystalline structure.\n\nIdeal for daily morning and evening Aarti, electric aroma diffusers, and meditation spaces. Known to repel airborne pathogens and fill the sanctum with crisp, therapeutic pine notes.",
                'price' => 299.00,
                'compare_price' => 499.00,
                'member_price' => 239.00,
                'stock' => 180,
                'sku' => '99P-BHIM-KPR-200',
                'badge' => '100% Pure Organic',
                'rating' => 4.95,
                'reviews_count' => 94,
                'is_gangajal' => false,
                'is_member_free_eligible' => false,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'purity_details' => '100% raw crystalline Bhimseni Kapoor. Fully sublimating formulation that leaves completely zero ash or soot.',
                'benefits' => 'Clears respiratory passages, acts as a natural mosquito and insect deterrent, and dispels negative energies during twilight prayers.',
                'specifications' => [
                    'Net Weight' => '200 grams',
                    'Form' => 'Natural Irregular Crystalline Flakes',
                    'Sublimation' => '100% Residue-Free Burn',
                    'Aroma' => 'Fresh Crisp Himalayan Pine & Camphor',
                ],
                'image_url' => '/images/products/bhimseni-camphor.jpg',
                'gallery' => [
                    '/images/products/bhimseni-camphor.jpg',
                ],
            ],

            // 3. Desi Cow Dung Guggal & Loban Hawan Cups
            [
                'category_key' => 'hawan-cups',
                'name' => 'Desi Cow Dung Guggal & Loban Hawan Cups (Pack of 15 with Free Fiber Stand)',
                'short_description' => 'Ready-to-burn charcoal-free mini hawan cups filled with pure Guggal, Loban, and sacred Vedic herbs.',
                'description' => "Perform an authentic mini Vedic Hawan at home in just 15 minutes. Made with a pure Desi Gir Cow dung base filled with organic Benzoin Loban resin, fragrant Guggal, camphor, and 21 Ayurvedic hawan samagri herbs.\n\nLight the rim with a matchstick and place it on the complimentary heat-resistant stand. The fragrant white smoke purifies indoor air and neutralizes negative vastu energies.",
                'price' => 219.00,
                'compare_price' => 349.00,
                'member_price' => 179.00,
                'stock' => 320,
                'sku' => '99P-HWN-CUP-15',
                'badge' => 'Vedic Purification',
                'rating' => 4.88,
                'reviews_count' => 142,
                'is_gangajal' => false,
                'is_member_free_eligible' => false,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'purity_details' => 'Handcrafted by rural artisans using Indigenous Desi Cow Gomaya and natural gum resins. 0% toxic charcoal or chemical sawdust.',
                'benefits' => 'Neutralizes negative energy, balances indoor humidity, kills household bacteria, and creates a tranquil temple-like aura.',
                'specifications' => [
                    'Quantity' => '15 Pre-filled Hawan Cups + 1 Burner Stand',
                    'Burn Duration' => '20 - 25 Minutes per cup',
                    'Charcoal Content' => '0% (Completely Charcoal-Free)',
                    'Fragrance' => 'Deep Temple Loban & Guggal Resins',
                ],
                'image_url' => '/images/products/hawan-cups.jpg',
                'gallery' => [
                    '/images/products/hawan-cups.jpg',
                ],
            ],

            // 4. Shuddha Kumkum, Roli & Gangajal Akshat Pooja Combo
            [
                'category_key' => 'chandan-tilak',
                'name' => 'Shuddha Temple Kumkum, Roli & Gangajal Akshat Pooja Combo (Set of 3 Jars)',
                'short_description' => 'Chemical-free organic turmeric kumkum, royal kasturi chandan powder, and Gangajal-purified unbroken akshat rice.',
                'description' => "Every Vedic ritual commences with sacred tilak. This three-piece set brings the authentic sanctity of Har Ki Pauri temples directly to your mandir.\n\nIncludes 100% organic turmeric-extracted blood red Kumkum (Roli), pure fragrant Chandan powder, and whole unbroken Akshat grains ritually sanctified in holy Brahmakund waters. Formulated without harsh chemicals or synthetic dyes.",
                'price' => 189.00,
                'compare_price' => 299.00,
                'member_price' => 149.00,
                'stock' => 210,
                'sku' => '99P-TILAK-SET-03',
                'badge' => 'Chemical Free',
                'rating' => 4.85,
                'reviews_count' => 58,
                'is_gangajal' => false,
                'is_member_free_eligible' => false,
                'is_active' => true,
                'is_featured' => false,
                'is_bestseller' => false,
                'purity_details' => 'Pure herbal formulation. Tested for heavy metals and toxic azo-dyes. Completely skin-friendly and safe for daily forehead tilak.',
                'benefits' => 'Cools the Ajna chakra (third eye), promotes clarity and calm, and invokes auspicious blessings of Goddess Lakshmi during puja.',
                'specifications' => [
                    'Set Contents' => '1x Shuddha Roli (50g), 1x Chandan Tilak (50g), 1x Gangajal Akshat (50g)',
                    'Ingredients' => 'Organic Turmeric, Lime extract, Natural Sandalwood, Whole Basmati Rice',
                    'Skin Safety' => 'Dermatologically Safe, Non-Irritating',
                ],
                'image_url' => '/images/products/tilak-combo.jpg',
                'gallery' => [
                    '/images/products/tilak-combo.jpg',
                ],
            ],

            // 5. Energized Gold Plated Kanak Dhara Yantra
            [
                'category_key' => 'yantras',
                'name' => 'Energized Gold Plated Kanak Dhara Yantra (3.5 x 3.5 Inch with Acrylic Stand)',
                'short_description' => 'Meticulously etched sacred geometric brass yantra electroplated in 24K gold foil for Maa Lakshmi\'s golden shower of blessings.',
                'description' => "The Kanak Dhara Yantra is one of the most revered prosperity mandalas in Sanatan Dharma, revealed by Adi Shankaracharya to eliminate financial hardships and debt.\n\nAccurately engraved on virgin brass plate with diamond-cut geometry and coated in lustrous 24K gold foil. Consecrated with 1008 Kanakadhara Stotram recitations. Perfect for placement in domestic mandirs, office desks, cash safes, and reception counters.",
                'price' => 399.00,
                'compare_price' => 699.00,
                'member_price' => 329.00,
                'stock' => 95,
                'sku' => '99P-YNT-KANAK-35',
                'badge' => 'Pran Pratishtha',
                'rating' => 4.96,
                'reviews_count' => 87,
                'is_gangajal' => false,
                'is_member_free_eligible' => false,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'purity_details' => 'High-precision CNC engraved pure virgin brass plate with 24K micron gold foil coating and clear tarnish-resistant protective seal.',
                'benefits' => 'Clears obstacles in wealth flow, mitigates business stagnation, and fosters peaceful abundance in household environments.',
                'specifications' => [
                    'Dimensions' => '3.5 Inch x 3.5 Inch (9 cm x 9 cm)',
                    'Material' => 'Solid Brass with 24K Gold Foil Layer',
                    'Display' => 'Includes Free Transparent Acrylic Table Stand',
                    'Energization' => 'Blessed during Friday Shuddha Lakshmi Abhishek',
                ],
                'image_url' => '/images/products/kanak-dhara-yantra.jpg',
                'gallery' => [
                    '/images/products/kanak-dhara-yantra.jpg',
                ],
            ],

            // 6. Certified Natural 7 Mukhi Nepali Rudraksha in Pure Silver Capping
            [
                'category_key' => 'rudraksha',
                'name' => 'Certified Natural 7 Mukhi Nepali Rudraksha in Pure Silver Capping',
                'short_description' => 'Authentic seven-faced Himalayan Rudraksha bead governed by Mahalakshmi and Venus, encased in pure 925 silver capping.',
                'description' => "Saat Mukhi (7 Mukhi) Rudraksha is directly blessed by Goddess Mahalakshmi and governed by planet Venus (Shukra). Sourced directly from authentic Himalayan groves of Nepal and handpicked for deep, symmetrical natural facets.\n\nPurified in sacred Brahmakund Gangajal at Haridwar. Enclosed in pure 925 Sterling Silver casing and strung on an energized red silk dori. Delivered with an individual authenticity lab test certificate.",
                'price' => 799.00,
                'compare_price' => 1399.00,
                'member_price' => 649.00,
                'stock' => 60,
                'sku' => '99P-RUD-7MUKHI-SILVER',
                'badge' => 'Lab Certified',
                'rating' => 4.98,
                'reviews_count' => 112,
                'is_gangajal' => false,
                'is_member_free_eligible' => false,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'purity_details' => '100% authentic Nepalese Elaeocarpus Ganitrus. Tested for natural density, seed compartments, and unbroken contours.',
                'benefits' => 'Neutralizes negative planetary influences, unlocks career and financial opportunities, and reduces stress and anxiety.',
                'specifications' => [
                    'Origin' => 'Nepal Himalayas (High Altitude)',
                    'Mukhi' => '7 Mukhi (Seven Natural Clefs)',
                    'Capping' => 'Hallmarked 925 Sterling Silver',
                    'Certificate' => 'Government accredited Gemology Lab Certificate Included',
                ],
                'image_url' => '/images/products/nepali-rudraksha.jpg',
                'gallery' => [
                    '/images/products/nepali-rudraksha.jpg',
                ],
            ],

            // 7. Solid Brass Borosilicate Glass Akhand Jyoti Diya
            [
                'category_key' => 'brass-diyas',
                'name' => 'Solid Brass Borosilicate Glass Akhand Jyoti Diya (Medium 5.5 Inch)',
                'short_description' => 'Heavy cast virgin brass deepak with heat-resistant borosilicate glass chimney for continuous 24-48 hr puja flame.',
                'description' => "Designed specifically for uninterrupted festive prayers, Navratri Akhand Jyot, and daily temple worship. Built with heavy-gauge solid virgin brass that maintains stability and remains cool to handle at the base.\n\nThe thermal-shock resistant borosilicate glass chimney prevents the flame from extinguishing due to ceiling fans or window drafts, while reducing oil evaporation and eliminating soot formation.",
                'price' => 449.00,
                'compare_price' => 699.00,
                'member_price' => 369.00,
                'stock' => 110,
                'sku' => '99P-BRS-AKHAND-55',
                'badge' => 'Windproof Design',
                'rating' => 4.91,
                'reviews_count' => 73,
                'is_gangajal' => false,
                'is_member_free_eligible' => false,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => false,
                'purity_details' => 'Cast in 100% lead-free virgin brass with a lab-tested borosilicate glass dome that withstands thermal shocks up to 350°C.',
                'benefits' => 'Enables safe, uninterrupted deepak illumination overnight during festivals without fire hazard or ghee spillage.',
                'specifications' => [
                    'Height' => '5.5 Inches (14 cm)',
                    'Weight' => '340 grams',
                    'Material' => '100% Solid Brass + Thermal Shock Glass',
                    'Oil/Ghee Capacity' => 'Up to 24-36 hrs continuous illumination',
                ],
                'image_url' => '/images/products/akhand-jyoti-diya.jpg',
                'gallery' => [
                    '/images/products/akhand-jyoti-diya.jpg',
                    '/images/products/ghee-diya-batti.jpg',
                ],
            ],

            // 8. Complete Vedic Rudrabhishek Puja Samagri Kit
            [
                'category_key' => 'daily-kits',
                'name' => 'Complete Vedic Rudrabhishek Puja Samagri Kit (21 Sacred Articles Box)',
                'short_description' => 'Hand-curated ritual kit containing Brahmakund Gangajal, Bhasma, Bilva leaves, Janeu, Attar, and everything for Shiva Abhishek.',
                'description' => "Carefully curated in consultation with veteran Har Ki Pauri temple priests to make Pradosh Vrat, Shravan Somwar, and Mahashivratri abhishek seamless and spiritually complete.\n\nContains all 21 prescribed Vedic items individually packed in tamper-proof pouches, including sealed Brahmakund Gangajal, pure Vibhuti bhasma, dry Bilva Patra powder, organic Attar, Janeu pair, Dhoop cups, Chandan, and a step-by-step mantra vidhi guide.",
                'price' => 599.00,
                'compare_price' => 999.00,
                'member_price' => 499.00,
                'stock' => 85,
                'sku' => '99P-KIT-RUDRABHISHEK-21',
                'badge' => 'Complete Ritual Box',
                'rating' => 4.97,
                'reviews_count' => 164,
                'is_gangajal' => false,
                'is_member_free_eligible' => false,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'purity_details' => 'Authentic materials curated according to Shiva Purana prescriptions. Features genuine untouched Gangajal from Har Ki Pauri.',
                'benefits' => 'Eliminates the stress of visiting multiple markets for rare puja samagri. Perfect for home or temple Rudra Abhishek ceremonies.',
                'specifications' => [
                    'Total Items' => '21 Prescribed Sacred Articles',
                    'Holy Water' => 'Included 250ml Brahmakund Gangajal bottle',
                    'Vedic Guide' => 'Shiva Rudrabhishek Vidhi booklet in Hindi & English',
                    'Box' => 'Premium hardbound commemorative gift box',
                ],
                'image_url' => '/images/products/rudrabhishek-puja-kit.jpg',
                'gallery' => [
                    '/images/products/rudrabhishek-puja-kit.jpg',
                ],
            ],
        ];

        // Process each product simulating Admin\ProductController::store() data entry
        foreach ($adminProductsPayload as $productData) {
            $catId = $categoryMap[$productData['category_key']] ?? null;

            if (! $catId) {
                $firstCat = Category::first();
                $catId = $firstCat ? $firstCat->id : 1;
            }

            unset($productData['category_key']);
            $productData['category_id'] = $catId;

            // Make sure 'image' column is set from 'image_url'
            $productData['image'] = $productData['image_url'] ?? $productData['image'] ?? null;
            unset($productData['image_url']);

            // Check if product already exists by SKU to maintain idempotence
            $existingProduct = Product::where('sku', $productData['sku'])->first();

            if ($existingProduct) {
                // Update existing record, keeping original admin slug
                $existingProduct->update($productData);
            } else {
                // Exact slug generation algorithm from Admin\ProductController::store() line 109:
                // $validated['slug'] = Str::slug($validated['name']).'-'.Str::lower(Str::random(5));
                $productData['slug'] = Str::slug($productData['name']).'-'.Str::lower(Str::random(5));

                Product::create($productData);
            }
        }
    }
}
