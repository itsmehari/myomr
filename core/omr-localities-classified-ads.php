<?php
/**
 * OMR Classified Ads — locality list for filters (OMR-first + wider India).
 */
if (!function_exists('getClassifiedAdsLocalities')) {
    function getClassifiedAdsLocalities(): array {
        return array_merge(
            [
                'Navalur', 'Sholinganallur', 'Thoraipakkam', 'Karapakkam', 'Perungudi',
                'Kelambakkam', 'Siruseri', 'Kandhanchavadi', 'Okkiyam Thuraipakkam',
            ],
            [
                'Chennai (other area)',
                'Tamil Nadu (outside Chennai)',
                'Other — India',
            ]
        );
    }
}

if (!function_exists('classifiedAdsLocalityToSlug')) {
    function classifiedAdsLocalityToSlug(string $locality): string {
        return strtolower(preg_replace('/\s+/', '-', trim($locality)));
    }
}

if (!function_exists('classifiedAdsSlugToLocality')) {
    /** Resolve URL slug back to canonical locality label; null if unknown. */
    function classifiedAdsSlugToLocality(string $slug): ?string {
        foreach (getClassifiedAdsLocalities() as $loc) {
            if (classifiedAdsLocalityToSlug($loc) === $slug) {
                return $loc;
            }
        }
        return null;
    }
}
