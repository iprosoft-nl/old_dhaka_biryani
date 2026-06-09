<?php
session_start();

// Set default language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

// Check if language is changed via URL or toggle
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if (in_array($lang, ['en', 'nl'])) {
        $_SESSION['lang'] = $lang;
    }
}

$current_lang = $_SESSION['lang'];

// Load translation files
$translations = [
    'en' => [
        'nav_home' => 'Home',
        'nav_about' => 'About Us',
        'nav_menu' => 'Menu',
        'nav_contact' => 'Contact',
        'hero_tagline' => 'Authentic Old Dhaka Biryani at burger meal prices!',
        'home_intro_title' => 'Biryani from the Heart of Our Kitchen to Your Table – With Love and Biryani.',
        'home_intro_p1' => "We believe that authentic Biryani should be as affordable as fast food, without ever compromising on taste or quality. That's why we’ve kept our profits minimal so that everyone can enjoy the richness of real Biryani at the price of a burger meal. Everything we cook is made fresh from scratch, only after we receive your order. Unlike most professional restaurants that serve pre-cooked, refrigerated meals reheated for speed, we take our time because you deserve a meal that’s made with care, not convenience.",
        'home_intro_p2' => "Thank you for your understanding and patience. We promise that after your very first bite, you'll want to reorder again and again.",
        'home_story_title' => 'Our Family Tradition',
        'home_story_p1' => "As a family passionate about cooking, we grew up preparing many different types of Biryani—each layered with spices, stories, and love. Whether it was Kacchi, Hyderabadi, Chicken, Lamb, Beef, or even Tuna Biryani, every dish had its own identity, and every bite carried the warmth of home.",
        'home_story_p2' => "We believe Biryani is more than just a meal — it’s a celebration of culture, a reminder of home, and a way to bring people together. Over the years, our kitchen became a place of joy, laughter, and shared memories, where the aroma of slow-cooked rice and marinated meat would draw friends and neighbors to the table.",
        'home_story_p3' => "Now, we want to bring that same experience to you. With every order, we aim to deliver not just food, but a piece of our heritage.",
        'about_title' => 'Our Story',
        'about_subtitle' => 'Established. Heritage in Every Bite',
        'chef_title' => 'Meet Our Chef',
        'chef_story' => 'With over 10 years of experience, our chef brings the authentic taste of South Asian recipes to your table. Every dish is a reflection of passion and family tradition.',
        'contact_title' => 'Contact Us',
        'opening_hours' => 'Opening Hours',
        'mon_thu' => 'Monday – Thursday',
        'fri_sat' => 'Friday – Saturday',
        'sun' => 'Sunday',
        'footer_rights' => 'All rights reserved.',
        'vat_notice' => 'Prices include 9% VAT (BTW)',
        'subtotal' => 'Subtotal',
        'vat' => 'VAT (9%)',
        'total' => 'Grand Total',
        'order_now' => 'Order Now',
        'halal_certified' => '100% Halal Food',
        'fresh_ingredients' => 'Fresh Ingredients',
        'homemade' => 'Homemade Cooking',
        'delivery_info' => 'Delivery within 8 km radius'
    ],
    'nl' => [
        'nav_home' => 'Home',
        'nav_about' => 'Over Ons',
        'nav_menu' => 'Menu',
        'nav_contact' => 'Contact',
        'hero_tagline' => 'Authentieke Old Dhaka Biryani voor de prijs van een burgermenu!',
        'home_intro_title' => 'Biryani uit het hart van onze keuken naar uw tafel – met liefde en Biryani.',
        'home_intro_p1' => "Wij geloven dat authentieke Biryani net zo betaalbaar moet zijn als fastfood, zonder ooit concessies te doen aan smaak of kwaliteit. Daarom hebben we onze winsten minimaal gehouden, zodat iedereen kan genieten van de rijkdom van echte Biryani voor de prijs van een burgermenu. Alles wat we koken wordt vers vanaf nul gemaakt, pas nadat we uw bestelling hebben ontvangen. In tegenstelling tot de meeste professionele restaurants die voorgekookte, gekoelde maaltijden serveren die voor de snelheid opnieuw worden opgewarmd, nemen wij onze tijd omdat u een maaltijd verdient die met zorg is gemaakt, niet uit gemak.",
        'home_intro_p2' => "Bedankt voor uw begrip en geduld. We beloven dat u na uw allereerste hap keer op keer zult willen nabestellen.",
        'home_story_title' => 'Onze Familietraditie',
        'home_story_p1' => "Als een familie met een passie voor koken, zijn we opgegroeid met het bereiden van veel verschillende soorten Biryani—elk gelaagd met kruiden, verhalen en liefde. Of het nu Kacchi, Hyderabadi, Kip, Lam, Rund of zelfs Tonijn Biryani was, elk gerecht had zijn eigen identiteit en elke hap droeg de warmte van thuis met zich mee.",
        'home_story_p2' => "Wij geloven dat Biryani meer is dan alleen een maaltijd — het is een viering van cultuur, een herinnering aan thuis en een manier om mensen samen te brengen. Door de jaren heen werd onze keuken een plek van vreugde, gelach en gedeelde herinneringen, waar het aroma van langzaam gekookte rijst en gemarineerd vlees vrienden en buren naar de tafel trok.",
        'home_story_p3' => "Nu willen we diezelfde ervaring naar u toe brengen. Met elke bestelling streven we ernaar om niet alleen eten, maar een stukje van ons erfgoed te leveren.",
        'about_title' => 'Ons Verhaal',
        'about_subtitle' => 'Gevestigd. Erfgoed in elke hap',
        'chef_title' => 'Ontmoet Onze Chef',
        'chef_story' => 'Met meer dan 10 jaar ervaring brengt onze chef de authentieke smaak van Zuid-Aziatische recepten naar uw tafel. Elk gerecht is een weerspiegeling van passie en familietraditie.',
        'contact_title' => 'Neem Contact Op',
        'opening_hours' => 'Openingstijden',
        'mon_thu' => 'Maandag – Donderdag',
        'fri_sat' => 'Vrijdag – Zaterdag',
        'sun' => 'Zondag',
        'footer_rights' => 'Alle rechten voorbehouden.',
        'vat_notice' => 'Prijzen zijn inclusief 9% BTW',
        'subtotal' => 'Subtotaal',
        'vat' => 'BTW (9%)',
        'total' => 'Totaalbedrag',
        'order_now' => 'Bestel Nu',
        'halal_certified' => '100% Halal Eten',
        'fresh_ingredients' => 'Verse Ingrediënten',
        'homemade' => 'Huisgemaakt Koken',
        'delivery_info' => 'Bezorging binnen een straal van 8 km'
    ]
];

function __($key) {
    global $translations, $current_lang;
    return $translations[$current_lang][$key] ?? $key;
}
?>
