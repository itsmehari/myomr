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
        'image_url' => 'https://m.media-amazon.com/images/I/61Q5oC1bqEL._SL1129_.jpg',
        'active' => true,
    ],
    [
        'id' => 'amz-himalaya-bhringaraja-hair-growth-oil',
        'network' => 'amazon',
        'title' => 'Himalaya Ayurveda Secrets Ayurveda Hair Growth Oil | With Bhringaraja, Hibiscus & Curry Leaf | Supports New Hair Growth & Reduces Damage | Paraben & Sulphate Free | 100 ml',
        'benefit' => 'Hair growth oil with Bhringaraja + hibiscus to support new growth and reduce damage.',
        'url' => 'https://www.amazon.in/Himalaya-Ayurveda-Bhringaraja-Hibiscus-Supports/dp/B0GFFBF6Q5?_encoding=UTF8&pd_rd_w=kvhon&content-id=amzn1.sym.9edad6de-f85d-41dd-a893-96a2a0223a93%3Aamzn1.symc.b1464ab7-6d6a-4fc8-be8f-f2e9bcc64228&pf_rd_p=9edad6de-f85d-41dd-a893-96a2a0223a93&pf_rd_r=2NJM23HNW4NEAYW6SGFZ&pd_rd_wg=42H1t&pd_rd_r=4faf5e0c-efe0-4fe0-a561-65d3cd0d43db&th=1&linkCode=ll2&tag=myomrstore-21&linkId=f9da239665f9fb24f8478c70fdcc91d2&ref_=as_li_ss_tl',
        'image_url' => '',
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
    'health' => [
        'match' => ['hair', 'himalaya', 'bhringaraja', 'hibiscus', 'growth', 'oil', 'skincare', 'beauty'],
        'product_ids' => ['amz-himalaya-bhringaraja-hair-growth-oil'],
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
            'amz-himalaya-bhringaraja-hair-growth-oil',
            'amz-honeycomb-wrap',
            'amz-dual-tip-markers',
            'amz-link-4-update',
            'amz-link-5-update',
            'amz-link-6-update',
        ],
    ],
];
