<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnalyticsEventsRequest;
use App\Models\AnalyticsEvent;
use Illuminate\Http\JsonResponse;

class AnalyticsIngestController extends Controller
{
    public function store(StoreAnalyticsEventsRequest $request): JsonResponse
    {
        $eventos = $request->eventosLimpios();

        if ($eventos !== []) {

            AnalyticsEvent::insert($eventos);
        }

        return response()->json(['stored' => count($eventos)], 202);
    }
}
