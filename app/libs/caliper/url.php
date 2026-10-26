<?php
namespace caliper;

/**
 * Strips user input from URLs before they are sent to the LRS.
 *
 * Query values and path segments can carry names (search terms) or text that
 * trips the LRS's WAF (../, 169.254.169.254), so only identifier-like parts
 * are kept.
 */
class CaliperUrl {
    const REDACTED = '_';

    /**
     * Keeps parameter names that are plain identifiers, with values redacted
     *
     * @param array $queryParams parsed query, e.g. from $_GET or parse_str()
     * @return array
     */
    public static function sanitizeQueryParams($queryParams) {
        $sanitized = array();
        foreach (array_keys((array) $queryParams) as $key) {
            if (preg_match('/^[A-Za-z0-9_-]{1,64}$/', (string) $key)) {
                $sanitized[$key] = '';
            }
        }
        return $sanitized;
    }

    /**
     * Keeps path segments that are plain identifiers (with an optional file
     * extension), keeps only the name of named params (sort:name -> sort:),
     * and replaces any other segment
     *
     * @param string $path
     * @return string
     */
    public static function sanitizePath($path) {
        $segments = array();
        foreach (explode('/', (string) $path) as $segment) {
            if ($segment === '' || preg_match('/^[A-Za-z0-9_-]+(\.[A-Za-z0-9]{1,10})?$/', $segment)) {
                $segments[] = $segment;
            } elseif (preg_match('/^([A-Za-z0-9_-]{1,64}):/', $segment, $match)) {
                $segments[] = $match[1] . ':';
            } else {
                $segments[] = self::REDACTED;
            }
        }
        return implode('/', $segments);
    }

    /**
     * Keeps scheme, host and port, cleans the path and query, and drops
     * credentials and fragment
     *
     * @param string $url
     * @return string|null null if not an http(s) URL
     */
    public static function sanitizeUrl($url) {
        $parts = parse_url((string) $url);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])
            || !in_array(strtolower($parts['scheme']), array('http', 'https'))
            || !preg_match('/^[A-Za-z0-9.-]+$/', $parts['host'])) {
            return null;
        }

        $sanitized = strtolower($parts['scheme']) . '://' . $parts['host']
            . (isset($parts['port']) ? ':' . $parts['port'] : '')
            . self::sanitizePath($parts['path'] ?? '/');

        parse_str($parts['query'] ?? '', $queryParams);
        $queryParams = self::sanitizeQueryParams($queryParams);
        if ($queryParams) {
            $sanitized .= '?' . http_build_query($queryParams);
        }
        return $sanitized;
    }
}
