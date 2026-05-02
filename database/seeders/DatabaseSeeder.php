<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        // Default site settings
        $defaults = [
            'site_name'        => 'SK Artistic Films',
            'site_tagline'     => 'Crafting Stories That Last Forever',
            'logo'             => '',
            'about_title'      => 'About SK Artistic Films',
            'about_text'       => 'We are a passionate film production studio dedicated to telling stories that inspire, challenge, and move audiences. Since our founding, we have brought over a decade of cinematic excellence to the screen.',
            'about_image'      => '',
            'founded_year'     => '2012',
            'hero_type'        => 'image',          // 'image', 'video_upload', 'youtube'
            'hero_image'       => '',
            'hero_video_file'  => '',
            'hero_youtube'     => '',
            'hero_title'       => 'SK Artistic Films',
            'hero_subtitle'    => 'Where Every Frame Tells a Story',
            'contact_email'    => 'contact@skartisticfilms.com',
            'contact_phone'    => '+92 300 0000000',
            'contact_address'  => 'Karachi, Pakistan',
            'facebook'         => '',
            'instagram'        => '',
            'youtube'          => '',
            'footer_text'      => '© 2024 SK Artistic Films. All Rights Reserved.',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::set($key, $value);
        }
    }
}