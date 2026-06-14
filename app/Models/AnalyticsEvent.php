<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'session_id', 'type', 'step_id', 'field_id', 'duration_ms',
        'resolved_category', 'verdict', 'error_code', 'device', 'lang',
        'feature', 'term', 'needs_review', 'meta', 'occurred_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'needs_review' => 'boolean',
        'occurred_at' => 'datetime',
        'duration_ms' => 'integer',
    ];
}
