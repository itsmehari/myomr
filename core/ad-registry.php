<?php
/**
 * Ad registry for MyOMR news article banner ads.
 * Monetization-ready: structure supports future DB migration.
 *
 * Each ad: id, advertiser, url, slot_ids, sizes, design (template key), active.
 */
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__));
}

$omr_ads = [
    [
        'id'        => 'resumedoctor-1',
        'advertiser' => 'ResumeDoctor',
        'url'       => 'https://resumedoctor.in',
        'slot_ids'  => ['article-top', 'article-mid'],
        'sizes'     => ['728x90', '336x280', '300x250'],
        'design'    => 'resumedoctor',
        'headline'  => 'Get Your Resume Reviewed',
        'tagline'   => 'Professional CV writing services',
        'active'    => true,
    ],
    [
        'id'        => 'mycovai-1',
        'advertiser' => 'MyCovAI',
        'url'       => 'https://mycovai.in',
        'slot_ids'  => ['article-top', 'article-mid'],
        'sizes'     => ['728x90', '336x280', '300x250'],
        'design'    => 'mycovai',
        'headline'  => 'Explore MyCovAI',
        'tagline'   => 'AI-powered solutions',
        'active'    => true,
    ],
    [
        'id'        => 'colourchemist-1',
        'advertiser' => 'ColourChemist',
        'url'       => 'https://colourchemist.co.in',
        'slot_ids'  => ['article-top', 'article-mid'],
        'sizes'     => ['728x90', '336x280', '300x250'],
        'design'    => 'colourchemist',
        'headline'  => 'Visit ColourChemist',
        'tagline'   => 'Precision chemicals & solutions',
        'active'    => true,
    ],
];
