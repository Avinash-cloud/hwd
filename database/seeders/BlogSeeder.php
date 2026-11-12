<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'The Sacred Significance of Haridwar Ganga Aarti',
                'slug' => 'sacred-significance-of-haridwar-ganga-aarti',
                'category' => 'Ganga Sanctity',
                'author_name' => 'Acharya Vidyadhar Shastri',
                'reading_time_minutes' => 6,
                'is_published' => true,
                'is_featured' => true,
                'published_at' => now()->subDays(5),
                'image' => 'images/haridwar-temples.jpg',
                'excerpt' => 'Discover why Haridwar is revered as the epicenter of spiritual energy, where drops of divine Amrit spilled during Samudra Manthan.',
                'content' => "<p class=\"lead\">For centuries, pilgrims from across the globe have journeyed to Haridwar with one burning aspiration: to touch and partake in the sanctified waters of Mother Ganga. But what makes these sacred ghats along Mother Ganga so exceptionally divine?</p>

<h3>The Cosmic Legend of Amrit Drops</h3>
<p>According to ancient Puranic texts, during the celestial churning of the ocean (Samudra Manthan), Lord Vishnu's vehicle Garuda flew across the heavens carrying the celestial pitcher of Amrit (the nectar of immortality). As the celestial forces pursued him, divine drops of nectar fell at four sacred spots on earth: Haridwar, Prayagraj, Ujjain, and Nashik.</p>
<p>In Haridwar, the exact spot where the sacred nectar touched Mother Ganga is the sacred pilgrimage ghat. Lord Brahma is also believed to have meditated here, leaving behind divine spiritual vibrations along the banks of the river.</p>

<h3>Scientific Purity of Haridwar's Ganga Water</h3>
<p>Modern scientific assays continue to corroborate what Vedic sages knew millennia ago. As the Ganga emerges from the high Himalayan glaciers of Gangotri through pristine quartzite and mineral-rich shale, the water at Haridwar possesses remarkable dissolved oxygen levels and bactericidal bacteriophages that prevent putrefaction.</p>
<blockquote>\"Water collected from Haridwar retains its purity, sweet taste, and ionic balance for years without spoiling or harboring coliform bacteria when stored in sealed copper or glass vessels.\"</blockquote>

<h3>How to Honor Haridwar Gangajal at Home</h3>
<p>To preserve the spiritual potency of holy water:</p>
<ul>
    <li>Always keep the bottle in your North-East (Ishanya) pooja altar.</li>
    <li>Never place the holy vessel on bare ground; always use a wooden or copper stand.</li>
    <li>Sprinkle a few drops during morning Sandhya Vandana, new home Griha Pravesh, or auspicious hawan ceremonies.</li>
</ul>",
            ],
            [
                'title' => 'Why Pure Copper Vessels are Essential for Storing Holy Ganga Water',
                'slug' => 'why-copper-vessels-are-essential-for-gangajal',
                'category' => 'Vedic Health',
                'author_name' => 'Pandit Devratna Sharma',
                'reading_time_minutes' => 5,
                'is_published' => true,
                'is_featured' => true,
                'published_at' => now()->subDays(12),
                'image' => 'images/haridwar-panorama-clocktower.jpg',
                'excerpt' => 'Ancient Ayurvedic treatises emphasize Tamra Jal (copper water). Learn the spiritual resonance and biophysical alchemy of keeping Gangajal in consecrated copper casks.',
                'content' => '<p class="lead">In virtually every traditional Hindu household, Gangajal is stored reverently in a copper lota or sealed brass vessel. This is not merely customary decorum—it is a sophisticated synthesis of spiritual consecration and Ayurvedic science.</p>

<h3>The Ayurvedic Alchemy of Tamra (Copper)</h3>
<p>Ayurvedic scriptures including the <em>Charaka Samhita</em> classify copper as a potent Rasayana with natural oligodynamic properties. When pristine water remains in contact with high-purity copper, micro-particles of ionic copper infuse into the water, alkalizing its pH and naturally eradicating microscopic contaminants.</p>

<h3>Spiritual Energy Conduction</h3>
<p>In Vedic energy science, copper is regarded as a superconductor of prana (vital life force). Copper vibrates in resonance with solar (Surya) frequencies, imparting vitality, clarity, and protective energy to anything it holds.</p>
<ul>
    <li><strong>Alkaline Balance:</strong> Naturally normalizes acidity and promotes cellular hydration.</li>
    <li><strong>Purity Conservation:</strong> Keeps Gangajal hermetically sanctified for decades.</li>
    <li><strong>Pranic Alignment:</strong> Amplifies sacred mantras chanted during abhishekam.</li>
</ul>

<h3>Proper Maintenance of Sacred Copper Casks</h3>
<p>To cleanse your altar copper vessel naturally without chemical abrasives, use a paste of pure pitambari, lemon juice, and rock salt. Rinse thoroughly with clear water and wipe dry with a soft cloth to maintain its radiant devotional glow.</p>',
            ],
            [
                'title' => 'Brahma Muhurta Aarti: The Science and Divine Energy Behind Dawn Rituals',
                'slug' => 'brahma-muhurta-aarti-science-and-divine-energy',
                'category' => 'Rituals & Traditions',
                'author_name' => 'Acharya Vidyadhar Shastri',
                'reading_time_minutes' => 7,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => now()->subDays(18),
                'image' => 'images/haridwar-temples.jpg',
                'excerpt' => 'At Brahma Muhurta (between 4:00 AM and 5:30 AM), the atmosphere at Haridwar is alive with transcendent serenity. Explore why our sacred batches are drawn exclusively during this auspicious window.',
                'content' => "<p class=\"lead\">Every morning before sunrise, as darkness gently dissolves over the Shivalik foothills, Haridwar awakens to the solemn conch shells and sacred chants of Brahma Muhurta. At Blu Bliss, this is the exact divine moment when our sacred batch collection takes place.</p>

<h3>What is Brahma Muhurta?</h3>
<p>Brahma Muhurta translates to 'the creator\'s hour'—occurring two muhurtas (approximately 1 hour and 36 minutes) before sunrise. During this period:</p>
<ul>
    <li>The atmosphere is saturated with nascent oxygen (O3 / nascent O2) and pristine negative ions.</li>
    <li>Cosmic electromagnetic disturbances are at their lowest point of the 24-hour cycle.</li>
    <li>The mind naturally experiences profound stillness, making mantra japa effortlessly potent.</li>
</ul>

<h3>The Sacred Dawn Collection Ritual</h3>
<p>Before drawing a single drop from sacred Haridwar ghats, our ordained Vedic priests perform the dawn Ganga Stotram chanting and offering of fragrant earthen deepams. The water is collected at mid-stream where the swift current is purest and cleanest, immediately filtered through micro-fine hermetic screens, and preserved in sanitary tanks awaiting consecration.</p>

<h3>Bringing the Dawn Energy into Your Home</h3>
<p>Even if you live thousands of kilometers away from Haridwar, you can invoke the same dawn sanctity by performing your morning pooja between 5:00 AM and 6:30 AM, sprinkling three drops of certified Haridwar Gangajal over your sacred altar.</p>",
            ],
            [
                'title' => 'Panchamrit & Hawan Samagri: Reviving Ancient Vedic Purification in Modern Homes',
                'slug' => 'panchamrit-and-hawan-samagri-vedic-purification',
                'category' => 'Spiritual Wisdom',
                'author_name' => 'Dr. Rajeshwari Joshi',
                'reading_time_minutes' => 5,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => now()->subDays(24),
                'image' => 'images/haridwar-panorama-clocktower.jpg',
                'excerpt' => 'How authentic hawan samagri composed of guggul, camphor, Gir cow ghee, and sacred herbs purifies household air quality and spiritual ambiance.',
                'content' => "<p class=\"lead\">The Vedic Yajna is not merely a ritual of sacrifice; it is an ecological and spiritual cleansing system developed by ancient rishis to revitalize living environments.</p>

<h3>The Therapeutic Components of Authentic Samagri</h3>
<p>True Vedic Hawan Samagri is composed of sacred botanicals known for their antimicrobial and aromatic qualities:</p>
<ul>
    <li><strong>Pure Guggul (Commiphora mukul):</strong> Cleanses dense energies and dispels negative vibrations.</li>
    <li><strong>Bhimseni Kapoor (Pure Camphor):</strong> Neutralizes airborne pollutants and creates mental clarity.</li>
    <li><strong>A2 Gir Cow Ghee:</strong> Produces high-frequency cleansing smoke that nourishes the prana.</li>
    <li><strong>Sandalwood & Jatamansi:</strong> Calms neurological stress and balances the nervous system.</li>
</ul>

<h3>Mini-Hawan for Daily Urban Devotion</h3>
<p>You don't need an elaborate pit to practice daily Agnihotra or micro-hawan. Using pre-formed herbal cow dung hawan cups infused with sacred herbs, you can complete a 10-minute cleansing ritual every Tuesday and Saturday to infuse your home with temple-like positivity.</p>",
            ],
            [
                'title' => 'The Story of King Bhagiratha: How Mother Ganga Descended to Earth',
                'slug' => 'story-of-king-bhagiratha-ganga-descent',
                'category' => 'Ganga Sanctity',
                'author_name' => 'Pandit Devratna Sharma',
                'reading_time_minutes' => 6,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => now()->subDays(30),
                'image' => 'images/haridwar-temples.jpg',
                'excerpt' => 'The epic tale of unwavering tapasya that brought the river of salvation down from Lord Shiva\'s matted locks to redeem humanity.',
                'content' => "<p class=\"lead\">The sacred name 'Bhagirathi' is immortalized in every Hindu heart as the symbol of relentless devotion and filial piety. It commemorates King Bhagiratha, whose penance liberated sixty thousand ancestors.</p>

<h3>The Burning Need for Redemption</h3>
<p>When the sixty thousand sons of King Sagara were reduced to ashes by the fiery gaze of Sage Kapila, their souls remained in spiritual limbo. Only the celestial waters of Akash Ganga, flowing through the heavens, could wash away their karmic residue and grant them Moksha.</p>

<h3>The Great Tapasya & Shiva\'s Grace</h3>
<p>For thousands of years, Prince Bhagiratha performed austere tapasya in the high Himalayas. Mother Ganga agreed to descend, but warned that the immense velocity of her torrent would shatter planet Earth. Bhagiratha then prayed to Lord Shiva, who compassionately opened his matted locks (Jata) to catch the torrential waters, allowing them to flow gently into seven sacred streams across Bharatvarsha.</p>

<h3>Haridwar: The Gateway to the Divine</h3>
<p>Where the Ganga leaves the towering Himalayan mountains and enters the plains of northern India, that gateway was named <strong>Gangadwara</strong>—today known as Haridwar. To drink her waters is to touch the very compassion of Lord Shiva and the motherly grace of Devi Ganga.</p>",
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::updateOrCreate(
                ['slug' => $blog['slug']],
                $blog
            );
        }
    }
}
