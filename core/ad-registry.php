<?php
/**
 * Ad registry for MyOMR banner ads (site-wide).
 * Monetization-ready: structure supports future DB migration.
 *
 * Each ad: id, advertiser, url, slot_ids, sizes, design (template key), active.
 * Sizes: 728x90, 336x280, 300x250, 320x50 (mobile leaderboard).
 */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
}

// All slot IDs used across user-facing pages (article, homepage, listings, detail pages, etc.)
$omr_ad_slot_ids = [
    'article-top', 'article-mid',
    'homepage-top', 'homepage-mid',
    'content-top', 'content-mid', 'listing-top', 'sidebar', 'global-top',
    'jobs-index-top', 'jobs-index-mid', 'jobs-detail-mid',
    'hostels-index-top', 'hostels-index-mid', 'hostels-detail-mid',
    'rent-lease-index-top', 'rent-lease-detail-mid',
    'buy-sell-index-top', 'buy-sell-detail-mid',
    'listings-index-top', 'listings-detail-mid',
    'events-index-top', 'events-index-mid', 'events-detail-mid',
    'coworking-index-top', 'coworking-detail-mid',
    'elections-top', 'elections-mid',
    'discover-top',
];

$omr_ads = [
    [
        'id'        => 'resumedoctor-1',
        'advertiser' => 'ResumeDoctor',
        'url'       => 'https://resumedoctor.in',
        'slot_ids'  => $omr_ad_slot_ids,
        'sizes'     => ['728x90', '336x280', '300x250', '320x50'],
        'design'    => 'resumedoctor',
        'headline'  => 'Get Your Resume Reviewed',
        'tagline'   => 'Professional CV writing services',
        'active'    => true,
    ],
    [
        'id'        => 'mycovai-1',
        'advertiser' => 'MyCovAI',
        'url'       => 'https://mycovai.in',
        'slot_ids'  => $omr_ad_slot_ids,
        'sizes'     => ['728x90', '336x280', '300x250', '320x50'],
        'design'    => 'mycovai',
        'headline'  => 'Explore MyCovAI',
        'tagline'   => 'AI-powered solutions',
        'active'    => true,
    ],
    [
        'id'        => 'colourchemist-1',
        'advertiser' => 'ColourChemist',
        'url'       => 'https://colourchemist.co.in',
        'slot_ids'  => $omr_ad_slot_ids,
        'sizes'     => ['728x90', '336x280', '300x250', '320x50'],
        'design'    => 'colourchemist',
        'headline'  => 'Visit ColourChemist',
        'tagline'   => 'Precision chemicals & solutions',
        'active'    => true,
    ],
];
