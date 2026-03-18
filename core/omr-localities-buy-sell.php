<?php
/**
 * OMR Buy-Sell — Centralized locality list.
 * Used by post-listing, process-listing, index, category, locality, sitemap.
 */
if (!function_exists('getBuySellLocalities')) {
    function getBuySellLocalities(): array {
        return [
            'Navalur',
            'Sholinganallur',
            'Thoraipakkam',
            'Karapakkam',
            'Perungudi',
            'Kelambakkam',
            'Siruseri',
            'Kandhanchavadi',
            'Okkiyam Thuraipakkam',
        ];
    }
}
