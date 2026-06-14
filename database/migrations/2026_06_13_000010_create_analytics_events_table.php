<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 64)->index();
            $table->string('type', 40)->index();
            $table->string('step_id', 40)->nullable();
            $table->string('field_id', 64)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('resolved_category', 32)->nullable();
            $table->string('verdict', 16)->nullable();
            $table->string('error_code', 40)->nullable();
            $table->string('device', 16)->nullable();
            $table->string('lang', 8)->nullable();
            $table->string('feature', 16)->nullable();
            $table->string('term', 60)->nullable();
            $table->boolean('needs_review')->default(false);
            $table->json('meta')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamp('created_at')->nullable();

            $table->index(['type', 'occurred_at']);
            $table->index(['step_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
