<?php

use App\Services\HealthCheckService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use App\Events\HealthCheckUpdated;

uses(Tests\TestCase::class);

beforeEach(function () {
    Cache::flush();
    Event::fake();
});

it('validates URLs correctly', function () {
    $service = new HealthCheckService();

    expect(fn() => $service->performChecks('invalid-url', 'test-session'))
        ->toThrow(Exception::class, 'Invalid URL');

    expect(fn() => $service->performChecks('ftp://example.com', 'test-session'))
        ->toThrow(Exception::class, 'Invalid URL');
});

it('blocks local IPs to prevent SSRF', function () {
    $service = new HealthCheckService();

    $localIps = [
        'http://127.0.0.1',
        'http://localhost',
        'http://10.0.0.1',
        'http://192.168.1.1',
        'http://172.16.0.1',
    ];

    foreach ($localIps as $ip) {
        expect(fn() => $service->performChecks($ip, 'test-session'))
            ->toThrow(Exception::class, 'Local or private IPs not allowed');
    }
});

it('performs HTTP checks successfully', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body><a href="/test">Test</a></body></html>', 200, [
            'Content-Type' => 'text/html',
            'X-Frame-Options' => 'DENY',
            'Strict-Transport-Security' => 'max-age=31536000',
        ]),
        'https://www.googleapis.com/pagespeedonline/*' => Http::response([
            'lighthouseResult' => [
                'categories' => [
                    'performance' => ['score' => 0.95],
                    'accessibility' => ['score' => 0.88],
                    'best-practices' => ['score' => 0.92],
                    'seo' => ['score' => 0.90],
                ],
                'audits' => [
                    'largest-contentful-paint' => ['numericValue' => 1200],
                    'first-contentful-paint' => ['numericValue' => 800],
                    'cumulative-layout-shift' => ['numericValue' => 0.05],
                    'interactive' => ['numericValue' => 2500],
                    'total-blocking-time' => ['numericValue' => 150],
                ],
            ],
        ], 200),
    ]);

    $service = new HealthCheckService();
    $results = $service->performChecks('https://example.com', 'test-session');

    expect($results)->toBeArray()
        ->and($results['status'])->toBe(200)
        ->and($results['url'])->toBe('https://example.com')
        ->and($results['responseTime'])->toBeGreaterThan(0)
        ->and($results['dnsTime'])->toBeGreaterThan(0);
});

it('analyzes security headers correctly', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body></body></html>', 200, [
            'Content-Security-Policy' => "default-src 'self'",
            'X-Frame-Options' => 'DENY',
            'Strict-Transport-Security' => 'max-age=31536000',
        ]),
    ]);

    $service = new HealthCheckService();
    $results = $service->performChecks('https://example.com', 'test-session');

    expect($results['securityHeaders'])->toBeArray()
        ->and($results['securityHeaders']['Content-Security-Policy']['status'])->toBe('pass')
        ->and($results['securityHeaders']['X-Frame-Options']['status'])->toBe('pass')
        ->and($results['securityHeaders']['Strict-Transport-Security']['status'])->toBe('pass')
        ->and($results['securityHeaders']['X-Content-Type-Options']['status'])->toBe('fail');
});

it('detects missing security headers', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body></body></html>', 200, []),
    ]);

    $service = new HealthCheckService();
    $results = $service->performChecks('https://example.com', 'test-session');

    foreach ($results['securityHeaders'] as $header) {
        expect($header['status'])->toBe('fail')
            ->and($header['present'])->toBe(false);
    }
});

it('extracts Lighthouse scores correctly', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body></body></html>', 200),
        'https://www.googleapis.com/pagespeedonline/*' => Http::response([
            'lighthouseResult' => [
                'categories' => [
                    'performance' => ['score' => 0.95],
                    'accessibility' => ['score' => 0.88],
                    'best-practices' => ['score' => 0.92],
                    'seo' => ['score' => 0.90],
                ],
                'audits' => [],
            ],
        ], 200),
    ]);

    $service = new HealthCheckService();
    $results = $service->performChecks('https://example.com', 'test-session');

    expect($results['performanceScore'])->toBe(95.0)
        ->and($results['accessibilityScore'])->toBe(88.0)
        ->and($results['bestPracticesScore'])->toBe(92.0)
        ->and($results['seoScore'])->toBe(90.0);
});

it('extracts Core Web Vitals metrics correctly', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body></body></html>', 200),
        'https://www.googleapis.com/pagespeedonline/*' => Http::response([
            'lighthouseResult' => [
                'categories' => [
                    'performance' => ['score' => 0.95],
                    'accessibility' => ['score' => 0.88],
                    'best-practices' => ['score' => 0.92],
                    'seo' => ['score' => 0.90],
                ],
                'audits' => [
                    'largest-contentful-paint' => ['numericValue' => 1234.56],
                    'first-contentful-paint' => ['numericValue' => 876.54],
                    'cumulative-layout-shift' => ['numericValue' => 0.123],
                    'interactive' => ['numericValue' => 2345.67],
                    'total-blocking-time' => ['numericValue' => 156.78],
                ],
            ],
        ], 200),
    ]);

    $service = new HealthCheckService();
    $results = $service->performChecks('https://example.com', 'test-session');

    expect($results['metrics']['lcp'])->toBe(1235.0)
        ->and($results['metrics']['fcp'])->toBe(877.0)
        ->and($results['metrics']['cls'])->toBe(0.123)
        ->and($results['metrics']['tti'])->toBe(2346.0)
        ->and($results['metrics']['tbt'])->toBe(157.0);
});

it('scans for broken links', function () {
    Http::fake([
        'https://example.com' => Http::response('
            <!DOCTYPE html>
            <html>
            <body>
                <a href="https://example.com/page1">Page 1</a>
                <a href="https://example.com/page2">Page 2</a>
                <a href="https://example.com/broken">Broken</a>
            </body>
            </html>
        ', 200),
        'https://example.com/page1' => Http::response('OK', 200),
        'https://example.com/page2' => Http::response('OK', 200),
        'https://example.com/broken' => Http::response('Not Found', 404),
    ]);

    $service = new HealthCheckService();
    $results = $service->performChecks('https://example.com', 'test-session');

    expect($results['brokenLinks'])->toBeArray()
        ->and(count($results['brokenLinks']))->toBe(1)
        ->and($results['brokenLinks'][0]['url'])->toBe('https://example.com/broken')
        ->and($results['brokenLinks'][0]['status'])->toBe(404);
});

it('caches results correctly', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body></body></html>', 200),
    ]);

    $service = new HealthCheckService();
    $service->performChecks('https://example.com', 'test-session');

    $urlHash = md5('https://example.com');
    $cacheKey = 'test-session_results_' . $urlHash;

    expect(Cache::has($cacheKey))->toBeTrue()
        ->and(Cache::get($cacheKey))->toBeArray();
});

it('broadcasts events on completion', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body></body></html>', 200),
    ]);

    Event::fake();

    $service = new HealthCheckService();
    $service->performChecks('https://example.com', 'test-session');

    Event::assertDispatched(HealthCheckUpdated::class, function ($event) {
        return $event->sessionId === 'test-session'
            && $event->urlHash === md5('https://example.com')
            && is_array($event->results);
    });
});

it('flags slow DNS resolution', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body></body></html>', 200),
    ]);

    $service = new HealthCheckService();
    $results = $service->performChecks('https://example.com', 'test-session');

    // DNS time should be recorded
    expect($results['dnsTime'])->toBeGreaterThanOrEqual(0);

    // dnsSlow flag should be boolean
    expect($results['dnsSlow'])->toBeBool();
});

it('updates recent checks history', function () {
    Http::fake([
        'https://example.com' => Http::response('<!DOCTYPE html><html><body></body></html>', 200),
    ]);

    $service = new HealthCheckService();
    $service->performChecks('https://example.com', 'test-session');

    $recentKey = 'test-session_recent';
    $recent = Cache::get($recentKey);

    expect($recent)->toBeArray()
        ->and(count($recent))->toBeGreaterThan(0)
        ->and($recent[0]['url'])->toBe('https://example.com');
});
