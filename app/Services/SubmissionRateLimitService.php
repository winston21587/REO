<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Researcher;

class SubmissionRateLimitService
{
    /**
     * Get current submission limits and usage for a researcher
     */
    public static function getSubmissionStatus(Researcher $researcher): array
    {
        $researcherId = $researcher->id;

        // Get actual submission timestamps to calculate reset times
        $submissionTimes = Cache::get("submission_times:{$researcherId}", []);
        $now = now()->timestamp;

        // Calculate hourly reset time (oldest submission in last hour)
        $hourlyResetSeconds = 3600;
        foreach ($submissionTimes as $timestamp) {
            if ($now - $timestamp < 3600) {
                $hourlyResetSeconds = min($hourlyResetSeconds, 3600 - ($now - $timestamp));
            }
        }

        // Calculate daily reset time (oldest submission in last 24 hours)
        $dailyResetSeconds = 86400;
        foreach ($submissionTimes as $timestamp) {
            if ($now - $timestamp < 86400) {
                $dailyResetSeconds = min($dailyResetSeconds, 86400 - ($now - $timestamp));
            }
        }

        // Cooldown reset time (5 seconds)
        $cooldownResetSeconds = 5;
        $cooldownTimestamp = Cache::get("submission_cooldown_time:{$researcherId}");
        if ($cooldownTimestamp) {
            $cooldownResetSeconds = max(0, 5 - ($now - $cooldownTimestamp));
        }

        return [
            'hourly' => [
                'limit' => 3,
                'current' => Cache::get("submission_hourly:{$researcherId}", 0),
                'remaining' => max(0, 3 - Cache::get("submission_hourly:{$researcherId}", 0)),
                'resets_in_seconds' => $hourlyResetSeconds,
            ],
            'daily' => [
                'limit' => 10,
                'current' => Cache::get("submission_daily:{$researcherId}", 0),
                'remaining' => max(0, 10 - Cache::get("submission_daily:{$researcherId}", 0)),
                'resets_in_seconds' => $dailyResetSeconds,
            ],
            'files' => [
                'max_per_submission' => 20,
                'max_size_mb' => 150,
            ],
            'is_in_cooldown' => Cache::has("submission_cooldown:{$researcherId}"),
            'cooldown_resets_in_seconds' => $cooldownResetSeconds,
        ];
    }

    /**
     * Get all submission attempts in the last 24 hours
     */
    public static function getRecentAttempts(Researcher $researcher): array
    {
        $attemptKey = "submission_attempts:{$researcher->id}";
        return Cache::get($attemptKey, []);
    }

    /**
     * Check if researcher can submit now
     */
    public static function canSubmit(Researcher $researcher): array
    {
        $status = self::getSubmissionStatus($researcher);

        $canSubmit = true;
        $reasons = [];

        if ($status['is_in_cooldown']) {
            $canSubmit = false;
            $reasons[] = 'Please wait a few seconds before submitting again.';
        }

        if ($status['hourly']['current'] >= $status['hourly']['limit']) {
            $canSubmit = false;
            $reasons[] = 'Hourly submission limit reached. Please try again in 1 hour.';
        }

        if ($status['daily']['current'] >= $status['daily']['limit']) {
            $canSubmit = false;
            $reasons[] = 'Daily submission limit reached (10 per day). Please try again tomorrow.';
        }

        return [
            'can_submit' => $canSubmit,
            'status' => $status,
            'reasons' => $reasons,
        ];
    }

    /**
     * Reset limits for a researcher (admin only)
     */
    public static function resetLimits(Researcher $researcher): void
    {
        $researcherId = $researcher->id;
        Cache::forget("submission_hourly:{$researcherId}");
        Cache::forget("submission_daily:{$researcherId}");
        Cache::forget("submission_cooldown:{$researcherId}");
    }

    /**
     * Clean up old cache entries (run via scheduler)
     */
    public static function cleanupOldEntries(): int
    {
        $count = 0;
        // Cache driver handles automatic expiration
        // This is just a placeholder for custom logic if needed
        return $count;
    }
}
