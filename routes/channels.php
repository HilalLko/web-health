<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('health-check.{sessionId}', function ($user, $sessionId) {
    return true; // Publicly accessible for the session owner (or effectively public since no login)
    // Ideally check if session()->getId() === $sessionId, but since we are broadcasting to public/presence channels or just using ID, 
    // and requirements say "no registration", we simply allow.
    // Actually, for public channels, we don't need this route unless using private channels.
    // But the event implements ShouldBroadcast, which uses private channels by default if using PrivateChannel.
    // The event uses `new Channel(...)` which is public.
    // So this file might not even be needed for public channels, but good to have if we switch to private.
});
