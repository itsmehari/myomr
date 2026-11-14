<?php
/**
 * Flyer / QR welcome landing — https://myomr.in/welcome
 * Copy and destinations for printed MyOMR promotions.
 */

if (!defined('MYOMR_WELCOME_PATH')) {
    define('MYOMR_WELCOME_PATH', '/welcome');
}

if (!function_exists('myomr_welcome_page_url')) {
    function myomr_welcome_page_url(): string
    {
        return 'https://myomr.in' . MYOMR_WELCOME_PATH;
    }
}

if (!function_exists('myomr_welcome_whatsapp_url')) {
    /**
     * WhatsApp invite with flyer UTM so print scans are measurable.
     */
    function myomr_welcome_whatsapp_url(string $utm_content = 'hero'): string
    {
        $base = defined('WHATSAPP_COMMUNITY_INVITE_URL')
            ? WHATSAPP_COMMUNITY_INVITE_URL
            : 'https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi';
        $params = [
            'utm_source' => 'flyer',
            'utm_medium' => 'print',
            'utm_campaign' => 'welcome_qr',
            'utm_content' => $utm_content,
        ];
        $q = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        return $base . (strpos($base, '?') !== false ? '&' : '?') . $q;
    }
}

if (!function_exists('myomr_welcome_destinations')) {
    /**
     * Primary tiles matching the printed flyer.
     *
     * @return array<int, array{id:string,label:string,blurb:string,href:string,count_key:string,cta:string}>
     */
    function myomr_welcome_destinations(): array
    {
        return [
            [
                'id' => 'news',
                'label' => 'Local news',
                'blurb' => 'Civic alerts, road work, metro and neighbourhood stories from your stretch of OMR.',
                'href' => '/local-news/news-highlights-from-omr-road.php',
                'count_key' => 'news',
                'cta' => 'Read local news',
            ],
            [
                'id' => 'jobs',
                'label' => 'Jobs around you',
                'blurb' => 'IT and non-IT roles, part-time and work-from-home openings near home — not city-wide noise.',
                'href' => '/omr-local-job-listings/jobs-hub-omr.php',
                'count_key' => 'jobs',
                'cta' => 'Find nearby jobs',
            ],
            [
                'id' => 'business',
                'label' => 'Local businesses',
                'blurb' => 'Schools, hospitals, restaurants, shops and services listed by OMR locality.',
                'href' => '/omr-listings/index.php',
                'count_key' => 'classifieds',
                'cta' => 'Explore businesses',
            ],
            [
                'id' => 'events',
                'label' => 'Events & community',
                'blurb' => 'Workshops, markets, apartment drives and things happening this week on the corridor.',
                'href' => '/local-events/',
                'count_key' => 'events',
                'cta' => 'See local events',
            ],
        ];
    }
}

if (!function_exists('myomr_welcome_more_links')) {
    /**
     * Secondary paths that still belong on a first-scan page.
     *
     * @return array<int, array{label:string,href:string}>
     */
    function myomr_welcome_more_links(): array
    {
        return [
            ['label' => 'Rent & lease', 'href' => '/omr-rent-lease/'],
            ['label' => 'Buy & sell', 'href' => '/omr-buy-sell/'],
            ['label' => 'Hostels & PGs', 'href' => '/omr-hostels-pgs/'],
            ['label' => 'Advertise', 'href' => '/advertise'],
        ];
    }
}

if (!function_exists('myomr_welcome_audiences')) {
    /**
     * @return array<int, array{label:string,blurb:string,href:string,cta:string}>
     */
    function myomr_welcome_audiences(): array
    {
        return [
            [
                'label' => 'I live on OMR',
                'blurb' => 'News, civic notes, events and a neighbourhood WhatsApp community.',
                'href' => '/omr-whatsapp-group',
                'cta' => 'Join the community',
            ],
            [
                'label' => 'I need work nearby',
                'blurb' => 'Browse openings along the corridor, then save a seeker profile.',
                'href' => '/omr-local-job-listings/jobs-hub-omr.php',
                'cta' => 'Open jobs',
            ],
            [
                'label' => 'I run a business',
                'blurb' => 'List your shop, post a job, or pick a weekly / monthly / yearly plan.',
                'href' => '/advertise',
                'cta' => 'See advertising',
            ],
        ];
    }
}

if (!function_exists('myomr_welcome_localities')) {
    /**
     * @return array<int, string>
     */
    function myomr_welcome_localities(): array
    {
        return [
            'Perungudi',
            'Thoraipakkam',
            'Sholinganallur',
            'Navalur',
            'Siruseri',
            'Kelambakkam',
            'Thiruporur',
        ];
    }
}

if (!function_exists('myomr_welcome_faq')) {
    /**
     * @return array<int, array{q:string,a:string}>
     */
    function myomr_welcome_faq(): array
    {
        return [
            [
                'q' => 'What is MyOMR?',
                'a' => 'MyOMR is a local platform for Old Mahabalipuram Road in Chennai. It brings news, jobs, business listings, rentals, events and community updates into one place for people who live or work on the corridor.',
            ],
            [
                'q' => 'Is MyOMR free after I scan the flyer?',
                'a' => 'Yes. Reading news, browsing jobs, checking events and searching listings is free. The WhatsApp community is free to join. Paid options are for businesses that want extra visibility — listings, classifieds, banners and campaigns.',
            ],
            [
                'q' => 'The flyer says ₹1 per day. What does that mean?',
                'a' => 'That line is about keeping local information cheap and close to home. You do not need to pay to start. Open the tiles below, join WhatsApp, or leave your email for a digest. Businesses who want promoted placement use the advertising page.',
            ],
            [
                'q' => 'How do I join the WhatsApp group?',
                'a' => 'Use the Join WhatsApp button on this page. It opens the official MyOMR community invite. If the group is full, email myomrnews@gmail.com and we will place you on the waitlist.',
            ],
            [
                'q' => 'Can I list my business or post a job?',
                'a' => 'Yes. Add a directory listing, post a job, or write to us for advertising. Start from the business path on this page, or open Advertise on MyOMR.',
            ],
        ];
    }
}
