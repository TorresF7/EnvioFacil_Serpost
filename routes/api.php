<?php

use App\Http\Controllers\Api\AnalyticsIngestController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:120,1')
    ->post('/analytics/events', [AnalyticsIngestController::class, 'store'])
    ->name('analytics.events');
