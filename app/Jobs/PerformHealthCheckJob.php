<?php

namespace App\Jobs;

use App\Services\HealthCheckService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Perform Health Check Job
 *
 * This queued job performs comprehensive website health checks asynchronously.
 * It analyzes HTTP status, SSL certificates, DNS resolution, security headers,
 * performance metrics via Google Lighthouse, and scans for broken links.
 *
 * The job is dispatched to a Redis queue and processed by a background worker,
 * allowing the web request to return immediately without blocking. Results are
 * broadcast to the client via WebSocket when the analysis completes.
 *
 * @package App\Jobs
 * @author Hilal Rauf
 * @version 1.0.0
 *
 * @see \App\Services\HealthCheckService The service that performs the actual checks
 * @see \App\Events\HealthCheckUpdated The event broadcast when check completes
 */
class PerformHealthCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The URL to be analyzed
     *
     * @var string The website URL (must be valid HTTP/HTTPS)
     */
    public $url;

    /**
     * The session ID of the user who initiated the check
     *
     * Used to associate results with the correct user session and
     * to determine the WebSocket channel for broadcasting results.
     *
     * @var string Laravel session identifier
     */
    public $sessionId;

    /**
     * Whether real-time monitoring is enabled
     *
     * If true, the job will schedule itself to run again in 5 minutes,
     * creating a continuous monitoring loop.
     *
     * @var bool True for continuous monitoring, false for one-time check
     */
    public $realTime;

    /**
     * Create a new job instance
     *
     * Initializes the job with the URL to check, session ID for result association,
     * and real-time monitoring flag. If no session ID is provided, it will attempt
     * to retrieve the current session ID.
     *
     * @param string $url The website URL to analyze
     * @param string|null $sessionId The user's session ID (auto-detected if null)
     * @param bool $realTime Whether to enable continuous monitoring (default: false)
     *
     * @return void
     *
     * @example
     * PerformHealthCheckJob::dispatch('https://example.com', session()->getId(), true);
     */
    public function __construct($url, $sessionId = null, $realTime = false)
    {
        $this->url = $url;
        $this->sessionId = $sessionId ?? session()->getId();
        $this->realTime = $realTime;
    }

    /**
     * Execute the job
     *
     * This method is called by the queue worker when the job is processed.
     * It delegates the actual health check to the HealthCheckService, which
     * performs all analysis tasks and broadcasts the results.
     *
     * The method includes error handling to log failures without crashing
     * the queue worker. If real-time monitoring is enabled, the service
     * will automatically schedule the next check.
     *
     * @param \App\Services\HealthCheckService $service The health check service (injected)
     *
     * @return void
     *
     * @throws \Exception May throw various exceptions during HTTP requests or API calls
     *
     * @see \App\Services\HealthCheckService::performChecks() The main analysis method
     *
     * @note This job can take 30-60 seconds to complete due to Lighthouse API calls
     * @note Exceptions are caught and logged to prevent queue worker failures
     */
    public function handle(HealthCheckService $service): void
    {
        // Log the start of the health check for debugging
        Log::info("Starting health check for {$this->url}");

        try {
            // Perform all health checks via the service
            // This includes HTTP, SSL, DNS, security headers, Lighthouse, and broken links
            $service->performChecks($this->url, $this->sessionId, $this->realTime);
        } catch (\Exception $e) {
            // Log the error without failing the job
            // This prevents one failed check from stopping the queue worker
            Log::error("Job failed for {$this->url}: " . $e->getMessage());

            // TODO: Optionally broadcast error state to the client
            // This would allow the UI to show a user-friendly error message
        }
    }
}
