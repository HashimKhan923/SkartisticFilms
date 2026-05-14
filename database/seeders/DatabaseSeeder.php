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



        \App\Models\Page::updateOrCreate(
    ['slug' => 'privacy-policy'],
    [
        'title'     => 'Privacy Policy',
        'is_active' => true,
        'content'   => '<h2>Privacy Policy</h2>
<p>Last updated: ' . date('F d, Y') . '</p>
<p>SK Artistic Films ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and share information about you when you visit our website.</p>

<h3>Information We Collect</h3>
<p>We may collect information you provide directly to us, such as your name and email address when you contact us through our website.</p>

<h3>How We Use Your Information</h3>
<p>We use the information we collect to respond to your inquiries, improve our website, and communicate with you about our films and productions.</p>

<h3>Information Sharing</h3>
<p>We do not sell, trade, or otherwise transfer your personal information to outside parties without your consent.</p>

<h3>Cookies</h3>
<p>Our website may use cookies to enhance your browsing experience. You can choose to disable cookies through your browser settings.</p>

<h3>Contact Us</h3>
<p>If you have any questions about this Privacy Policy, please contact us at ' . (\App\Models\SiteSetting::get('contact_email') ?? 'contact@skartisticfilms.com') . '</p>',
    ]
);

\App\Models\Page::updateOrCreate(
    ['slug' => 'terms-of-use'],
    [
        'title'     => 'Terms of Use',
        'is_active' => true,
        'content'   => '<h2>Terms of Use</h2>
<p>Last updated: ' . date('F d, Y') . '</p>
<p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>

<h3>Use of Content</h3>
<p>All content on this website including films, images, text and other media are the property of SK Artistic Films and are protected by copyright law.</p>

<h3>Disclaimer</h3>
<p>The information on this website is provided on an "as is" basis. SK Artistic Films makes no warranties regarding the accuracy or completeness of any information on this website.</p>

<h3>Contact Us</h3>
<p>If you have any questions about these Terms, please contact us at ' . (\App\Models\SiteSetting::get('contact_email') ?? 'contact@skartisticfilms.com') . '</p>',
    ]
);
    }
}