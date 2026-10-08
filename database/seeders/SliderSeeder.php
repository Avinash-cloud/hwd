<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Pure, Untouched Gangajal for Your Daily Abhishek',
                'subtitle' => 'Directly from Har Ki Pauri · Brahmakund Consecration',
                'description' => 'Collected at Brahma Muhurta during morning Ganga Aarti from Har Ki Pauri. Hermetically sealed, lab tested, and trackable via unique batch QR codes.',
                'image' => 'images/haridwar-panorama-clocktower.jpg',
                'button_text' => 'Join 5-Year Membership (₹500)',
                'button_link' => '/membership/join',
                'secondary_button_text' => 'Explore Holy Waters',
                'secondary_button_link' => '/products?category=sacred-gangajal',
                'badge_color' => 'amber',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Ready-To-Use Hawan Cups & Pure Cotton Battis',
                'subtitle' => '100% Charcoal-Free & Desi Cow Dung Base',
                'description' => 'Infuse your home with sacred Vedic vibrations in just 15 minutes. Premium cow dung hawan cups with pure loban, guggal, and long-burning cotton wicks.',
                'image' => 'images/brahmakund-ghat-shrines.jpg',
                'button_text' => 'Shop Hawan Specials',
                'button_link' => '/products?category=hawan-samagri',
                'secondary_button_text' => 'Diya Wicks & Ghee',
                'secondary_button_link' => '/products?category=puja-essentials',
                'badge_color' => 'rose',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Authentic Rudraksha & Pure Brass Aarti Lamps',
                'subtitle' => 'Energized in Haridwar Altar Prayers',
                'description' => '5-Mukhi Indonesian Rudraksha Japa Malas, pure Karungali wood bracelets, and heavy hand-cast brass panchamukhi diyas with peacock handles.',
                'image' => 'images/ganga-holy-waters.jpg',
                'button_text' => 'Explore Spiritual Store',
                'button_link' => '/products?category=spiritual-items',
                'secondary_button_text' => 'Brass Pooja Ware',
                'secondary_button_link' => '/products?category=brass-pooja-ware',
                'badge_color' => 'sky',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Slider::updateOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }
    }
}
