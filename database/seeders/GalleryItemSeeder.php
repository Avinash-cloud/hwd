<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GalleryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => 'Maha Sandhya Aarti at Haridwar',
                'caption' => 'Thousands of devout pilgrims gather at dusk along the sacred banks of Haridwar to witness the iconic multi-tiered brass lamp Aarti.',
                'media_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'image' => 'images/haridwar-temples.jpg',
                'category' => 'Ganga Aarti',
                'location' => 'Sacred Ganga Ghat, Haridwar',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Dawn Holy Water Collection',
                'caption' => 'Certified Vedic priests collecting holy Gangajal in consecrated copper vessels during the auspicious Brahma Muhurta window.',
                'media_type' => 'image',
                'image' => 'images/haridwar-panorama-clocktower.jpg',
                'category' => 'Ganga Sourcing',
                'location' => 'Sacred Ganga Ghat, Haridwar',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Vedic Sankalp & Consecration Ceremony',
                'caption' => 'Priests performing the sacred Vedic purification ceremony with 108 Gayatri mantra recitations over fresh batches of holy water.',
                'media_type' => 'image',
                'image' => 'images/haridwar-temples.jpg',
                'category' => 'Temple Darshan',
                'location' => 'Haridwar Vedic Ashram',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Hermetic Cleanroom Bottling Process',
                'caption' => 'Zero-human-touch automated bottling line ensuring medical-grade sanitary packaging without compromising spiritual sanctity.',
                'media_type' => 'image',
                'image' => 'images/haridwar-panorama-clocktower.jpg',
                'category' => 'Sanctified Bottling',
                'location' => 'Blu Bliss Facility, Haridwar',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Golden Floating Deepams on the River Ganga',
                'caption' => 'Earthen lamps made with marigold petals and cow ghee floating serenely down the holy river as evening prayers conclude.',
                'media_type' => 'image',
                'image' => 'images/haridwar-temples.jpg',
                'category' => 'Ganga Aarti',
                'location' => 'Haridwar Main Ghat',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Sacred Charan Paduka Shrine',
                'caption' => 'The revered stone imprint of Lord Vishnu\'s divine footprints at Haridwar\'s most sacred pilgrimage site.',
                'media_type' => 'image',
                'image' => 'images/haridwar-panorama-clocktower.jpg',
                'category' => 'Temple Darshan',
                'location' => 'Sacred Pilgrimage Shrine, Haridwar',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Ganga Aarti Brass Lamps & Vedic Chants',
                'caption' => 'Aarti priests lifting blazing camphor lamps in sync with traditional Vedic chants, illuminating the night sky.',
                'media_type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'image' => 'images/haridwar-temples.jpg',
                'category' => 'Ganga Aarti',
                'location' => 'Ganga Mandir Ghat, Haridwar',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Morning Himalayan Mist over Mother Ganga',
                'caption' => 'The crystal-clear, glacier-fed currents entering Haridwar from the upper reaches of Devprayag and Rishikesh.',
                'media_type' => 'image',
                'image' => 'images/haridwar-panorama-clocktower.jpg',
                'category' => 'Ganga Sourcing',
                'location' => 'Shivalik Foothills, Haridwar',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            GalleryItem::updateOrCreate(
                ['title' => $item['title']],
                $item
            );
        }
    }
}
