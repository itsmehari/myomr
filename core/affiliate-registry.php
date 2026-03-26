<?php
/**
 * Affiliate registry for content recommendations.
 * Keeps products and targeting rules out of component code.
 *
 * Image URLs: use m.media-amazon.com with _AC_SL500_ (or similar). Filenames containing "+" must be
 * normalized in the component (encoded as %2B). Prefer stable media-amazon paths over legacy
 * images-eu.ssl-images-amazon.com hotlinks (hotlink/referrer issues on some browsers).
 */

$omr_affiliate_products = [
    [
        'id' => 'amz-gyro-ball',
        'network' => 'amazon',
        'title' => 'Gadget Deals Gyro Ball Wrist Exerciser',
        'benefit' => 'Improves wrist, forearm and grip strength in short daily sessions.',
        'url' => 'https://amzn.to/3Q0ydlz',
        'image_url' => 'https://m.media-amazon.com/images/I/61TfQ5QfW6L._AC_SL500_.jpg',
        'active' => true,
    ],
    [
        'id' => 'amz-honeycomb-wrap',
        'network' => 'amazon',
        'title' => 'GLUN Honeycomb Paper Bubble Wrap Roll',
        'benefit' => 'Eco-friendly packing roll for moving, shipping and small business dispatch.',
        'url' => 'https://amzn.to/3Pz9MeW',
        'image_url' => 'https://m.media-amazon.com/images/I/71D7Fgxy+0L._AC_SL500_.jpg',
        'active' => true,
    ],
    [
        'id' => 'amz-dual-tip-markers',
        'network' => 'amazon',
        'title' => 'UCRAVO Dual Tip Art Markers (120 Colors)',
        'benefit' => 'Useful for visual notes, sketching, design drafts and creative work.',
        'url' => 'https://amzn.to/47pz8lB',
        'image_url' => 'https://m.media-amazon.com/images/I/71M4m7i6gvL._AC_SL500_.jpg',
        'active' => true,
    ],
    [
        'id' => 'amz-link-4-update',
        'network' => 'amazon',
        'title' => 'Amazon Featured Product (Update Name)',
        'benefit' => 'Replace with a clear product benefit line for better CTR.',
        'url' => 'https://amzn.to/3PwrQX8',
        'image_url' => '',
        'active' => true,
    ],
    [
        'id' => 'amz-link-5-update',
        'network' => 'amazon',
        'title' => 'Amazon Featured Product (Update Name)',
        'benefit' => 'Replace with a clear product benefit line for better CTR.',
        'url' => 'https://amzn.to/3PGPVKP',
        'image_url' => '',
        'active' => true,
    ],
    [
        'id' => 'amz-link-6-update',
        'network' => 'amazon',
        'title' => 'Amazon Featured Product (Update Name)',
        'benefit' => 'Replace with a clear product benefit line for better CTR.',
        'url' => 'https://amzn.to/4s5Z4uc',
        'image_url' => '',
        'active' => true,
    ],
];

/**
 * Targeting map:
 * - key: segment id
 * - match: category/tag/title keywords (lowercase compare in component)
 * - product_ids: product pool for that segment
 */
$omr_affiliate_targeting = [
    'career' => [
        'match' => ['career', 'job', 'it', 'interview', 'resume', 'fresher', 'hiring'],
        'product_ids' => ['amz-gyro-ball', 'amz-dual-tip-markers'],
    ],
    'education' => [
        'match' => ['school', 'student', 'college', 'exam', 'study', 'education'],
        'product_ids' => ['amz-dual-tip-markers'],
    ],
    'business' => [
        'match' => ['business', 'startup', 'shop', 'retail', 'seller', 'entrepreneur'],
        'product_ids' => ['amz-honeycomb-wrap'],
    ],
    'default' => [
        'match' => [],
        'product_ids' => [
            'amz-gyro-ball',
            'amz-honeycomb-wrap',
            'amz-dual-tip-markers',
            'amz-link-4-update',
            'amz-link-5-update',
            'amz-link-6-update',
        ],
    ],
];
