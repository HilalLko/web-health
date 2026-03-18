<?php

namespace App\Services;

use App\Events\HealthCheckUpdated;
use App\Jobs\PerformHealthCheckJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\SslCertificate\SslCertificate;

/**
 * Health Check Service
 *
 * This service performs comprehensive website health checks including:
 * - HTTP status and response time analysis
 * - SSL certificate validation
 * - DNS resolution performance
 * - Security headers analysis (CSP, HSTS, X-Frame-Options, etc.)
 * - Google Lighthouse performance metrics
 * - Core Web Vitals (LCP, FID, CLS, FCP, TTI, TBT)
 * - Broken links scanning
 *
 * Results are cached for 1 hour and broadcast to the client via WebSocket.
 * The service includes SSRF protection to prevent internal network access.
 *
 * @package App\Services
 * @author Hilal Rauf
 * @version 1.0.0
 *
 * @see \App\Jobs\PerformHealthCheckJob The job that calls this service
 * @see \App\Events\HealthCheckUpdated The event broadcast when check completes
 */
class HealthCheckService
{
    /**
     * Perform comprehensive health checks on a website
     *
     * This is the main entry point for all health check operations.
     * It orchestrates all individual checks, caches results, broadcasts
     * to the client, and optionally schedules recurring checks.
     *
     * @param string $url The website URL to analyze (must be valid HTTP/HTTPS)
     * @param string $sessionId The user's session ID for result association
     * @param bool $realTime Whether to enable continuous monitoring (default: false)
     *
     * @return array Complete health check results
     *
     * @throws \Exception If URL is invalid or points to local/private IP
     *
     * @example
     * $service = new HealthCheckService();
     * $results = $service->performChecks('https://example.com', session()->getId(), false);
     *
     * @note This method can take 30-60 seconds due to Lighthouse API calls
     * @note Results are automatically cached for 1 hour
     */
    public function performChecks($url, $sessionId, $realTime = false)
    {
        // Validate URL format
        $parsed = parse_url($url);
        if (!isset($parsed['scheme']) || !in_array($parsed['scheme'], ['http', 'https']) || filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception('Invalid URL');
        }

        // Block local IPs to prevent SSRF attacks
        $host = $parsed['host'];
        if ($this->isLocalIp($host)) {
            throw new \Exception('Local or private IPs not allowed');
        }

        // Initialize results array with default values
        $results = [
            'url' => $url,
            'status' => null,
            'responseTime' => null,
            'dnsTime' => null,
            'dnsRecords' => [],
            'dnsSlow' => false,
            'dnsError' => null,
            'sslValid' => null,
            'expiration' => null,
            'issuer' => null,
            'sslError' => null,
            'headers' => [],
            'securityHeaders' => [],
            'performanceScore' => null,
            'accessibilityScore' => null,
            'bestPracticesScore' => null,
            'seoScore' => null,
            'metrics' => [
                'lcp' => null,
                'fid' => null,
                'cls' => null,
                'fcp' => null,
                'tti' => null,
                'tbt' => null,
            ],
            'brokenLinks' => [],
            'timestamp' => now()->toIso8601String(),
        ];

        try {
            // ===== DNS Resolution Checks =====
            $dnsStart = microtime(true);
            $ip = @gethostbyname($host);
            $results['dnsTime'] = round((microtime(true) - $dnsStart) * 1000, 2);

            // Flag slow DNS resolution (>100ms)
            if ($results['dnsTime'] > 100) {
                $results['dnsSlow'] = true;
            }

            // Check if DNS resolution failed (returns hostname if failed)
            if ($ip === $host) {
                $results['dnsError'] = 'DNS resolution failed (NXDOMAIN)';
            }

            // Retrieve DNS records (A, AAAA, MX, NS)
            try {
                $dnsRecords = @dns_get_record($host, DNS_A + DNS_AAAA + DNS_MX + DNS_NS);
                $results['dnsRecords'] = $dnsRecords ?: [];
            } catch (\Exception $e) {
                $results['dnsRecords'] = [];
                $results['dnsError'] = $e->getMessage();
            }

            // ===== HTTP Request Checks =====
            $start = microtime(true);
            try {
                $response = Http::timeout(15)->withOptions(['verify' => false])->get($url);
                $results['responseTime'] = round((microtime(true) - $start) * 1000, 2);
                $results['status'] = $response->status();
                $results['headers'] = $response->headers();

                // Analyze security headers
                $results['securityHeaders'] = $this->analyzeSecurityHeaders($results['headers']);

                // Scan for broken links if status is successful
                if ($response->successful()) {
                    $results['brokenLinks'] = $this->scanBrokenLinks($url, $response->body());
                }
            } catch (\Exception $e) {
                $results['status'] = 0;
                $results['error'] = $e->getMessage();
                $results['responseTime'] = round((microtime(true) - $start) * 1000, 2);
            }

            // ===== SSL Certificate Checks =====
            if ($parsed['scheme'] === 'https') {
                try {
                    $certificate = SslCertificate::createForHostName($host);
                    $results['sslValid'] = $certificate->isValid();
                    $results['issuer'] = $certificate->getIssuer();
                    $results['expiration'] = $certificate->expirationDate()->format('D M d Y H:i:s');
                } catch (\Exception $e) {
                    $results['sslValid'] = false;
                    $results['sslError'] = $e->getMessage();
                }
            }

            // ===== Google Lighthouse Performance Checks =====
            try {
                $lighthouseData = $this->getLighthouseData($url);
                if ($lighthouseData) {
                    $results['performanceScore'] = $lighthouseData['performanceScore'];
                    $results['accessibilityScore'] = $lighthouseData['accessibilityScore'];
                    $results['bestPracticesScore'] = $lighthouseData['bestPracticesScore'];
                    $results['seoScore'] = $lighthouseData['seoScore'];
                    $results['metrics'] = $lighthouseData['metrics'];
                }
            } catch (\Exception $e) {
                Log::warning("Lighthouse check failed for {$url}: " . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error("Health check failed for {$url}: " . $e->getMessage());
            $results['error'] = $e->getMessage();
        }

        // ===== Cache Results =====
        $urlHash = md5($url);
        $cacheKey = $sessionId . '_results_' . $urlHash;
        Cache::put($cacheKey, $results, now()->addHour());

        // Store in recent checks (session-specific, keep last 10)
        $recentKey = $sessionId . '_recent';
        $recent = Cache::get($recentKey, []);
        array_unshift($recent, [
            'url' => $url,
            'status' => $results['status'] ?? 0,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'hash' => $urlHash,
        ]);
        $recent = array_slice($recent, 0, 10);
        Cache::put($recentKey, $recent, now()->addHour());

        // ===== Broadcast Results to Client =====
        broadcast(new HealthCheckUpdated($sessionId, $urlHash, $results));

        // ===== Schedule Next Check (Real-Time Monitoring) =====
        if ($realTime) {
            PerformHealthCheckJob::dispatch($url, $sessionId, $realTime)
                ->delay(now()->addMinutes(5));
        }

        return $results;
    }

    /**
     * Check if a hostname resolves to a local or private IP address
     *
     * This method provides SSRF (Server-Side Request Forgery) protection
     * by blocking requests to internal network addresses.
     *
     * @param string $host The hostname or IP address to check
     *
     * @return bool True if the host is local/private, false otherwise
     *
     * @example
     * $this->isLocalIp('localhost');      // true
     * $this->isLocalIp('192.168.1.1');    // true
     * $this->isLocalIp('google.com');     // false
     *
     * @note Blocks: localhost, 127.0.0.1, ::1, and all private IP ranges
     */
    private function isLocalIp(string $host): bool
    {
        // Resolve hostname to IP
        $ip = gethostbyname($host);

        // Block localhost
        if ($ip === '127.0.0.1' || $ip === '::1' || $host === 'localhost') {
            return true;
        }

        // Block private IP ranges (10.x.x.x, 192.168.x.x, 172.16-31.x.x)
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return true;
        }

        return false;
    }

    /**
     * Analyze HTTP response headers for security best practices
     *
     * Checks for the presence and configuration of 6 critical security headers:
     * - Content-Security-Policy (CSP)
     * - X-Frame-Options
     * - Strict-Transport-Security (HSTS)
     * - X-Content-Type-Options
     * - Referrer-Policy
     * - Permissions-Policy
     *
     * @param array $headers HTTP response headers from the website
     *
     * @return array Security headers analysis with status and recommendations
     *
     * @example
     * $analysis = $this->analyzeSecurityHeaders($response->headers());
     * // Returns:
     * [
     *     'Content-Security-Policy' => [
     *         'present' => false,
     *         'value' => null,
     *         'status' => 'fail',
     *         'recommendation' => 'Add CSP header...'
     *     ],
     *     ...
     * ]
     */
    private function analyzeSecurityHeaders(array $headers): array
    {
        $securityHeaders = [];

        // Content-Security-Policy
        $securityHeaders['Content-Security-Policy'] = [
            'present' => isset($headers['Content-Security-Policy']),
            'value' => $headers['Content-Security-Policy'][0] ?? null,
            'status' => isset($headers['Content-Security-Policy']) ? 'pass' : 'fail',
            'recommendation' => isset($headers['Content-Security-Policy'])
                ? 'Header is present and configured'
                : 'Add Content-Security-Policy header to prevent XSS and other injection attacks',
        ];

        // X-Frame-Options
        $securityHeaders['X-Frame-Options'] = [
            'present' => isset($headers['X-Frame-Options']),
            'value' => $headers['X-Frame-Options'][0] ?? null,
            'status' => isset($headers['X-Frame-Options']) ? 'pass' : 'fail',
            'recommendation' => isset($headers['X-Frame-Options'])
                ? 'Header is present and configured'
                : 'Add X-Frame-Options header to prevent clickjacking attacks',
        ];

        // Strict-Transport-Security (HSTS)
        $securityHeaders['Strict-Transport-Security'] = [
            'present' => isset($headers['Strict-Transport-Security']),
            'value' => $headers['Strict-Transport-Security'][0] ?? null,
            'status' => isset($headers['Strict-Transport-Security']) ? 'pass' : 'fail',
            'recommendation' => isset($headers['Strict-Transport-Security'])
                ? 'Header is present and configured'
                : 'Add Strict-Transport-Security header to enforce HTTPS connections',
        ];

        // X-Content-Type-Options
        $securityHeaders['X-Content-Type-Options'] = [
            'present' => isset($headers['X-Content-Type-Options']),
            'value' => $headers['X-Content-Type-Options'][0] ?? null,
            'status' => isset($headers['X-Content-Type-Options']) ? 'pass' : 'fail',
            'recommendation' => isset($headers['X-Content-Type-Options'])
                ? 'Header is present and configured'
                : 'Add X-Content-Type-Options: nosniff to prevent MIME type sniffing',
        ];

        // Referrer-Policy
        $securityHeaders['Referrer-Policy'] = [
            'present' => isset($headers['Referrer-Policy']),
            'value' => $headers['Referrer-Policy'][0] ?? null,
            'status' => isset($headers['Referrer-Policy']) ? 'pass' : 'fail',
            'recommendation' => isset($headers['Referrer-Policy'])
                ? 'Header is present and configured'
                : 'Add Referrer-Policy header to control referrer information',
        ];

        // Permissions-Policy
        $securityHeaders['Permissions-Policy'] = [
            'present' => isset($headers['Permissions-Policy']),
            'value' => $headers['Permissions-Policy'][0] ?? null,
            'status' => isset($headers['Permissions-Policy']) ? 'pass' : 'fail',
            'recommendation' => isset($headers['Permissions-Policy'])
                ? 'Header is present and configured'
                : 'Add Permissions-Policy header to control browser features',
        ];

        return $securityHeaders;
    }

    /**
     * Fetch Google Lighthouse performance data via PageSpeed Insights API
     *
     * Makes an API call to Google's PageSpeed Insights to get comprehensive
     * performance metrics including Lighthouse scores and Core Web Vitals.
     *
     * @param string $url The website URL to analyze
     *
     * @return array|null Lighthouse data with scores and metrics, or null on failure
     *
     * @throws \Exception If API key is missing or API call fails
     *
     * @example
     * $data = $this->getLighthouseData('https://example.com');
     * // Returns:
     * [
     *     'performanceScore' => 87,
     *     'accessibilityScore' => 95,
     *     'bestPracticesScore' => 100,
     *     'seoScore' => 90,
     *     'metrics' => [
     *         'lcp' => 1234.5,
     *         'fcp' => 876.2,
     *         ...
     *     ]
     * ]
     *
     * @note Requires PAGESPEED_API_KEY in .env
     * @note This call can take 30-60 seconds to complete
     */
    private function getLighthouseData(string $url): ?array
    {
        $apiKey = config('services.pagespeed.api_key');
        if (!$apiKey) {
            Log::warning('PageSpeed API key not configured');
            return null;
        }

        try {
            $response = Http::timeout(60)->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                'url' => $url,
                'key' => $apiKey,
                'category' => ['performance', 'accessibility', 'best-practices', 'seo'],
            ]);

            if (!$response->successful()) {
                Log::warning("PageSpeed API returned {$response->status()} for {$url}");
                return null;
            }

            $data = $response->json();
            $lighthouse = $data['lighthouseResult'] ?? null;

            if (!$lighthouse) {
                return null;
            }

            // Extract Lighthouse scores (0-1 scale, convert to 0-100)
            $categories = $lighthouse['categories'] ?? [];
            $audits = $lighthouse['audits'] ?? [];

            return [
                'performanceScore' => isset($categories['performance']['score']) ? round($categories['performance']['score'] * 100) : null,
                'accessibilityScore' => isset($categories['accessibility']['score']) ? round($categories['accessibility']['score'] * 100) : null,
                'bestPracticesScore' => isset($categories['best-practices']['score']) ? round($categories['best-practices']['score'] * 100) : null,
                'seoScore' => isset($categories['seo']['score']) ? round($categories['seo']['score'] * 100) : null,
                'metrics' => [
                    'lcp' => $audits['largest-contentful-paint']['numericValue'] ?? null,
                    'fid' => $audits['max-potential-fid']['numericValue'] ?? null,
                    'cls' => $audits['cumulative-layout-shift']['numericValue'] ?? null,
                    'fcp' => $audits['first-contentful-paint']['numericValue'] ?? null,
                    'tti' => $audits['interactive']['numericValue'] ?? null,
                    'tbt' => $audits['total-blocking-time']['numericValue'] ?? null,
                ],
            ];
        } catch (\Exception $e) {
            Log::error("Lighthouse API error for {$url}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Scan the homepage for broken links
     *
     * Parses the HTML to extract all links, checks up to 50 of them,
     * and returns a list of broken links (4xx and 5xx status codes).
     *
     * @param string $baseUrl The base URL of the website
     * @param string $html The HTML content of the homepage
     *
     * @return array List of broken links with URLs and status codes (max 20)
     *
     * @example
     * $brokenLinks = $this->scanBrokenLinks('https://example.com', $html);
     * // Returns:
     * [
     *     ['url' => 'https://example.com/404', 'status' => 404],
     *     ['url' => 'https://example.com/error', 'error' => 'Connection timeout'],
     *     ...
     * ]
     *
     * @note Checks maximum 50 links for performance
     * @note Returns maximum 20 broken links
     * @note Uses HEAD requests for speed
     */
    private function scanBrokenLinks(string $baseUrl, string $html): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');

        $brokenLinks = [];
        $checked = 0;
        $maxToCheck = 50; // Limit for performance
        $maxToReturn = 20; // Limit results

        foreach ($links as $link) {
            if ($checked >= $maxToCheck) {
                break;
            }

            // Ensure we have a DOMElement before calling getAttribute
            if (!($link instanceof \DOMElement)) {
                continue;
            }

            $href = $link->getAttribute('href');
            if (empty($href) || str_starts_with($href, '#') || str_starts_with($href, 'javascript:') || str_starts_with($href, 'mailto:')) {
                continue;
            }

            // Convert relative URLs to absolute
            $absoluteUrl = $this->makeAbsoluteUrl($href, $baseUrl);

            try {
                // Use HEAD request for speed
                $response = Http::timeout(5)->head($absoluteUrl);
                $status = $response->status();

                // Flag 4xx and 5xx errors as broken
                if ($status >= 400) {
                    $brokenLinks[] = [
                        'url' => $absoluteUrl,
                        'status' => $status,
                    ];
                }
            } catch (\Exception $e) {
                $brokenLinks[] = [
                    'url' => $absoluteUrl,
                    'error' => $e->getMessage(),
                ];
            }

            $checked++;
        }

        return array_slice($brokenLinks, 0, $maxToReturn);
    }

    /**
     * Convert a relative URL to an absolute URL
     *
     * Handles various URL formats including protocol-relative,
     * absolute paths, and relative paths.
     *
     * @param string $url The URL to convert (may be relative or absolute)
     * @param string $base The base URL to use for conversion
     *
     * @return string The absolute URL
     *
     * @example
     * $this->makeAbsoluteUrl('/about', 'https://example.com');
     * // Returns: 'https://example.com/about'
     *
     * $this->makeAbsoluteUrl('//cdn.example.com/img.jpg', 'https://example.com');
     * // Returns: 'https://cdn.example.com/img.jpg'
     *
     * $this->makeAbsoluteUrl('https://other.com/page', 'https://example.com');
     * // Returns: 'https://other.com/page' (already absolute)
     */
    private function makeAbsoluteUrl(string $url, string $base): string
    {
        // Already absolute
        if (parse_url($url, PHP_URL_SCHEME) !== null) {
            return $url;
        }

        // Protocol-relative URL
        if (str_starts_with($url, '//')) {
            $scheme = parse_url($base, PHP_URL_SCHEME);
            return $scheme . ':' . $url;
        }

        // Absolute path
        if (str_starts_with($url, '/')) {
            $parts = parse_url($base);
            return $parts['scheme'] . '://' . $parts['host'] . $url;
        }

        // Relative path
        return rtrim($base, '/') . '/' . ltrim($url, '/');
    }
}
