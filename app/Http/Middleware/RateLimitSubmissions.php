<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitSubmissions
{
    /**
     * Handle submission rate limiting to prevent spam.
     * 
     * Strategy:
     * 1. Prevent duplicate submissions (same title within 24 hours)
     * 2. Soft throttle: max 10 submissions per 24 hours per researcher
     * 3. Hard throttle: max 3 submissions per 1 hour (prevents rapid-fire spam)
     * 4. Cooldown: 5 second minimum between submissions (prevents accidental double-click)
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to submission endpoints
        if (!$this->isSubmissionRequest($request)) {
            return $next($request);
        }

        // Must be authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $researcher = $user->researcher;

        if (!$researcher) {
            return response()->json([
                'error' => 'Not registered as a researcher.',
            ], 403);
        }

        // 1. CHECK: Cooldown (5 second minimum between submissions)
        $cooldownKey = "submission_cooldown:{$researcher->id}";
        if (Cache::has($cooldownKey)) {
            return response()->json([
                'error' => 'Please wait a few seconds before submitting again.',
                'retry_after' => 5,
            ], 429);
        }

        // 2. CHECK: Hard throttle (3 per hour - prevents rapid-fire spam attacks)
        $hourlyKey = "submission_hourly:{$researcher->id}";
        $hourlyCount = Cache::get($hourlyKey, 0);
        
        if ($hourlyCount >= 3) {
            return response()->json([
                'error' => 'Too many submissions in a short time. Please try again in 1 hour.',
                'retry_after' => 3600,
            ], 429);
        }

        // 3. CHECK: Daily soft limit (10 per 24 hours - reasonable for researchers)
        $dailyKey = "submission_daily:{$researcher->id}";
        $dailyCount = Cache::get($dailyKey, 0);
        
        if ($dailyCount >= 10) {
            return response()->json([
                'error' => 'Daily submission limit reached (10 per day). Please try again tomorrow.',
                'limit' => 10,
                'current' => $dailyCount,
                'retry_after' => 86400,
            ], 429);
        }

        // 4. CHECK: Duplicate submission prevention (same title in last 24 hours)
        if ($this->isDuplicateSubmission($researcher->id, $request)) {
            return response()->json([
                'error' => 'A research protocol with this title was already submitted recently. Submit a different protocol or update the existing one.',
            ], 409);
        }

        // 5. CHECK: File size limits (max 150MB total per submission)
        $totalFileSize = $this->calculateTotalFileSize($request);
        if ($totalFileSize > 157286400) { // 150MB in bytes
            return response()->json([
                'error' => 'Total file size exceeds 150MB limit.',
                'size_limit_mb' => 150,
                'current_size_mb' => round($totalFileSize / 1048576, 2),
            ], 413);
        }

        // 6. CHECK: File count limit (max 20 files per submission)
        $fileCount = $this->countUploadedFiles($request);
        if ($fileCount > 20) {
            return response()->json([
                'error' => 'Too many files. Maximum 20 files per submission.',
                'file_limit' => 20,
                'current_files' => $fileCount,
            ], 422);
        }

        // All checks passed - increment counters now (before request is processed)
        // This ensures counters are updated regardless of whether the submission succeeds
        
        // Store submission timestamp for accurate reset time calculation
        $submissionTimes = Cache::get("submission_times:{$researcher->id}", []);
        $submissionTimes[] = now()->timestamp;
        // Keep only timestamps from last 24 hours
        $submissionTimes = array_filter($submissionTimes, function($ts) {
            return time() - $ts < 86400;
        });
        Cache::put("submission_times:{$researcher->id}", $submissionTimes, 86400);
        
        // Update cooldown (5 seconds)
        Cache::put($cooldownKey, true, 5);
        Cache::put("submission_cooldown_time:{$researcher->id}", now()->timestamp, 5);

        // Update hourly counter (resets after 1 hour)
        $hourlyCount = Cache::get($hourlyKey, 0);
        Cache::put($hourlyKey, $hourlyCount + 1, 3600);

        // Update daily counter (resets after 24 hours)
        $dailyCount = Cache::get($dailyKey, 0);
        Cache::put($dailyKey, $dailyCount + 1, 86400);

        // Log submission attempt for audit trail
        $this->logSubmissionAttempt($researcher->id, 'attempted', null);

        // Proceed with request
        return $next($request);
    }

    /**
     * Check if this is a submission request
     */
    private function isSubmissionRequest(Request $request): bool
    {
        return $request->isMethod('post') && (
            $request->routeIs('submit.title') ||
            $request->routeIs('submit.revisions')
        );
    }

    /**
     * Check if researcher submitted the same title recently (within 24 hours)
     */
    private function isDuplicateSubmission(int $researcherId, Request $request): bool
    {
        $title = $request->input('Study_Protocol_title');
        if (!$title) {
            return false;
        }

        // Check if same title exists from this researcher in last 24 hours
        $recentSubmission = \App\Models\Research_title::where('researcher_id', $researcherId)
            ->where('Study_Protocol_title', $title)
            ->where('created_at', '>=', now()->subDay())
            ->first();

        return $recentSubmission !== null;
    }

    /**
     * Calculate total file size from request
     */
    private function calculateTotalFileSize(Request $request): int
    {
        $total = 0;

        // Sum all file fields in the request (handles nested arrays like files[])
        $addSize = function ($items) use (&$addSize, &$total) {
            foreach ($items as $item) {
                if (is_array($item)) {
                    $addSize($item);
                } elseif ($item instanceof \Illuminate\Http\UploadedFile && $item->isValid()) {
                    $total += $item->getSize();
                }
            }
        };
        $addSize($request->allFiles());

        return $total;
    }

    /**
     * Count total files in submission
     */
    private function countUploadedFiles(Request $request): int
    {
        $count = 0;

        foreach ($request->allFiles() as $fileArray) {
            if (!is_array($fileArray)) {
                $count++;
            } else {
                $count += count($fileArray);
            }
        }

        return $count;
    }

    /**
     * Log submission attempts for audit trail and spam detection
     */
    private function logSubmissionAttempt(int $researcherId, string $status, ?int $responseCode): void
    {
        try {
            // Optional: Store submission attempts in a table for analytics
            // DB::table('submission_attempts')->insert([
            //     'researcher_id' => $researcherId,
            //     'status' => $status,
            //     'response_code' => $responseCode,
            //     'ip_address' => request()->ip(),
            //     'user_agent' => request()->userAgent(),
            //     'created_at' => now(),
            // ]);

            // For now, track in cache for immediate patterns
            $attemptKey = "submission_attempts:{$researcherId}";
            $attempts = Cache::get($attemptKey, []);
            $attempts[] = [
                'status' => $status,
                'timestamp' => now()->timestamp,
            ];

            // Keep only last 100 attempts
            $attempts = array_slice($attempts, -100);
            Cache::put($attemptKey, $attempts, 86400); // 24 hours
        } catch (\Exception $e) {
            // Silently fail - don't block submission if logging fails
            \Log::warning('Submission logging failed: ' . $e->getMessage());
        }
    }
}
