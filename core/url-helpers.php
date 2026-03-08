<?php
/**
 * URL Helper Functions for MyOMR.in
 * Centralized functions for generating canonical URLs and handling URL normalization
 */

/**
 * Get the canonical URL for the current page
 * Always returns https://myomr.in (non-www) version
 * 
 * @param string $path Optional path (defaults to current request URI)
 * @return string Canonical URL
 */
function get_canonical_url($path = null) {
    // Base canonical domain (always https, non-www)
    $base = 'https://myomr.in';
    
    // If path is provided, use it
    if ($path !== null) {
        // Remove leading slash if present, we'll add it
        $path = ltrim($path, '/');
        return $base . '/' . $path;
    }
    
    // Get current request URI
    $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
    
    // Remove query string for canonical URL (unless it's needed for the page)
    $request_uri = strtok($request_uri, '?');
    
    // Remove trailing slash (except for root)
    if ($request_uri !== '/' && substr($request_uri, -1) === '/') {
        $request_uri = rtrim($request_uri, '/');
    }
    
    // Handle index.php - redirect to clean URL
    if (basename($request_uri) === 'index.php') {
        $request_uri = dirname($request_uri);
        if ($request_uri === '.' || $request_uri === '/') {
            $request_uri = '/';
        }
    }
    
    return $base . $request_uri;
}

/**
 * Get the current page URL (with query string if needed)
 * 
 * @param bool $include_query_string Whether to include query string
 * @return string Current page URL
 */
function get_current_url($include_query_string = false) {
    $base = 'https://myomr.in';
    $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
    
    if (!$include_query_string) {
        $request_uri = strtok($request_uri, '?');
    }
    
    return $base . $request_uri;
}

/**
 * Normalize URL to canonical format
 * Removes www, forces https, removes trailing slashes (except root)
 * 
 * @param string $url URL to normalize
 * @return string Normalized URL
 */
function normalize_url($url) {
    // Parse URL
    $parsed = parse_url($url);
    
    if (!$parsed) {
        return $url;
    }
    
    // Force https
    $scheme = 'https';
    
    // Remove www from host
    $host = isset($parsed['host']) ? preg_replace('/^www\./', '', $parsed['host']) : 'myomr.in';
    
    // Get path
    $path = isset($parsed['path']) ? $parsed['path'] : '/';
    
    // Remove trailing slash except for root
    if ($path !== '/' && substr($path, -1) === '/') {
        $path = rtrim($path, '/');
    }
    
    // Handle index.php
    if (basename($path) === 'index.php') {
        $path = dirname($path);
        if ($path === '.' || $path === '/') {
            $path = '/';
        }
    }
    
    // Rebuild URL
    $normalized = $scheme . '://' . $host . $path;
    
    // Add query string if present and needed
    if (isset($parsed['query'])) {
        // For canonical URLs, we typically don't include query strings
        // But this function can be used for other purposes
        // $normalized .= '?' . $parsed['query'];
    }
    
    return $normalized;
}

