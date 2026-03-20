<?php

namespace App\Http\Controllers;

use App\Jobs\PerformHealthCheckJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Health Monitor Controller
 *
 * This controller handles the main website health monitoring functionality.
 * It manages the home page display, initiates health checks via background jobs,
 * and generates PDF reports of the analysis results.
 *
 * @package App\Http\Controllers
 * @author Hilal Rauf
 * @version 1.0.0
 */
class HealthMonitorController extends Controller
{
    /**
     * Display the home page with recent health checks
     *
     * This method renders the main application page using Inertia.js.
     * It retrieves the user's recent health checks from the cache and
     * passes them to the Vue component along with the session ID for
     * WebSocket communication.
     *
     * @return \Inertia\Response The Inertia response with Home component and props
     *
     * @example
     * Route::get('/', [HealthMonitorController::class, 'index']);
     */
    public function index()
    {
        // Get the current session ID for user-specific data
        $sessionId = session()->getId();

        // Render the Home Vue component with recent checks and session ID
        return Inertia::render('Home', [
            'recent' => Cache::get($sessionId . '_recent', []),
            'sessionId' => $sessionId,
        ]);
    }

    /**
     * Initiate a website health check
     *
     * This method validates the submitted URL, dispatches a background job
     * to perform the health check, and returns the user to the same page.
     * The actual health check is performed asynchronously by a queue worker,
     * and results are sent to the client via WebSocket broadcast.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing URL and realTime flag
     *
     * @return \Illuminate\Http\RedirectResponse Redirects back to the previous page
     *
     * @throws \Illuminate\Validation\ValidationException If URL validation fails
     *
     * @see \App\Jobs\PerformHealthCheckJob The background job that performs the check
     * @see \App\Events\HealthCheckUpdated The event broadcast when check completes
     *
     * @example
     * POST /check
     * {
     *     "url": "https://example.com",
     *     "realTime": true
     * }
     */
    public function check(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'url' => 'required|url',       // Must be a valid URL format
            'realTime' => 'boolean',       // Optional boolean for real-time monitoring
            'captcha_token' => 'required|string|in:verified_human' // Ensure CAPTCHA was passed
        ], [
            'captcha_token.required' => 'Please complete the human verification check.',
            'captcha_token.in' => 'Invalid human verification token.'
        ]);

        // Extract the real-time monitoring flag (defaults to false)
        $realTime = $request->boolean('realTime', false);

        // Get the current session ID for result association
        $sessionId = session()->getId();

        // Dispatch the health check job to the queue for background processing
        // This allows the controller to return immediately without waiting
        PerformHealthCheckJob::dispatch($request->url, $sessionId, $realTime);

        // Return to the previous page (Inertia handles this gracefully)
        return back();
    }

    /**
     * Download a PDF report of health check results
     *
     * This method retrieves cached health check results using the URL hash
     * and session ID, generates a PDF report using the DomPDF library,
     * and returns it as a downloadable file.
     *
     * @param string $urlHash MD5 hash of the checked URL (used as cache key)
     *
     * @return \Illuminate\Http\Response PDF download response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 404 if results not found or expired
     *
     * @see \App\Services\HealthCheckService::performChecks() Where results are cached
     *
     * @example
     * GET /download/5d41402abc4b2a76b9719d911017c592
     * Returns: website-health-report.pdf
     *
     * @note Results are cached for 1 hour. After expiration, this will return 404.
     * @note The cache key format is: {sessionId}_results_{urlHash}
     */
    public function download($urlHash)
    {
        // Get the current session ID
        $sessionId = session()->getId();

        // Construct the cache key for this specific result
        $cacheKey = $sessionId . '_results_' . $urlHash;

        // Attempt to retrieve the cached results
        $results = Cache::get($cacheKey);

        // If results don't exist or have expired, return 404
        if (!$results) {
            abort(404, 'Report not found or expired');
        }

        // Generate PDF from the Blade template with results data
        $pdf = Pdf::loadView('reports.health-report', compact('results'));

        // Return the PDF as a downloadable file
        return $pdf->download('website-health-report.pdf');
    }
}
