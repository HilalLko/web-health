<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Health Check Updated Event
 *
 * This event is broadcast via WebSocket when a health check completes.
 * It sends the complete analysis results to the client in real-time,
 * allowing the UI to update without requiring a page refresh.
 *
 * The event uses Laravel Reverb (WebSocket server) to push data to the
 * client's browser via Laravel Echo. Each user session has its own channel
 * to ensure results are only sent to the user who initiated the check.
 *
 * @package App\Events
 * @author Hilal Rauf
 * @version 1.0.0
 *
 * @see \App\Jobs\PerformHealthCheckJob Where this event is broadcast from
 * @see \App\Services\HealthCheckService The service that generates the results
 */
class HealthCheckUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The session ID of the user who initiated the check
     *
     * Used to determine which WebSocket channel to broadcast to,
     * ensuring results are only sent to the correct user.
     *
     * @var string Laravel session identifier
     */
    public $sessionId;

    /**
     * MD5 hash of the checked URL
     *
     * Used as a cache key for retrieving results and generating
     * the PDF download link.
     *
     * @var string MD5 hash (32 characters)
     */
    public $urlHash;

    /**
     * Complete health check results
     *
     * Contains all analysis data including HTTP status, SSL info,
     * DNS metrics, security headers, Lighthouse scores, Core Web Vitals,
     * and broken links.
     *
     * @var array Associative array with all health check data
     *
     * @example
     * [
     *     'url' => 'https://example.com',
     *     'status' => 200,
     *     'responseTime' => 234.56,
     *     'sslValid' => true,
     *     'performanceScore' => 87,
     *     'securityHeaders' => [...],
     *     'metrics' => [...],
     *     'brokenLinks' => [...]
     * ]
     */
    public $results;

    /**
     * Create a new event instance
     *
     * Initializes the event with the session ID, URL hash, and complete
     * health check results. These properties are automatically serialized
     * and sent to the client via WebSocket.
     *
     * @param string $sessionId The user's session ID for channel routing
     * @param string $urlHash MD5 hash of the checked URL
     * @param array $results Complete health check analysis results
     *
     * @return void
     *
     * @example
     * broadcast(new HealthCheckUpdated($sessionId, $urlHash, $results));
     */
    public function __construct($sessionId, $urlHash, $results)
    {
        $this->sessionId = $sessionId;
        $this->urlHash = $urlHash;
        $this->results = $results;
    }

    /**
     * Get the channels the event should broadcast on
     *
     * Returns an array of broadcast channels. This event uses a public channel
     * named "health-check.{sessionId}" to ensure each user only receives their
     * own results.
     *
     * The channel name format allows the frontend to subscribe to the correct
     * channel using the session ID passed from the server.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel> Array of broadcast channels
     *
     * @example
     * // Frontend subscription (JavaScript):
     * window.Echo.channel(`health-check.${sessionId}`)
     *     .listen('HealthCheckUpdated', (e) => {
     *         console.log(e.results);
     *     });
     *
     * @note Using public Channel instead of PrivateChannel for simplicity
     * @note Channel name pattern: "health-check.{sessionId}"
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('health-check.' . $this->sessionId),
        ];
    }
}
