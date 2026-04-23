<?php

namespace App\Http\Controllers;

use App\Services\SubmissionRateLimitService;
use Illuminate\Support\Facades\Auth;

class SubmissionStatusController extends Controller
{
    /**
     * Get current submission status and limits for authenticated researcher
     * 
     * GET /api/submission-status
     * 
     * Returns JSON with:
     * {
     *   "can_submit": true/false,
     *   "status": {
     *     "hourly": { "limit": 3, "current": 1, "remaining": 2 },
     *     "daily": { "limit": 10, "current": 5, "remaining": 5 },
     *     "files": { "max_per_submission": 20, "max_size_mb": 150 },
     *     "is_in_cooldown": false
     *   },
     *   "reasons": []
     * }
     */
    public function getStatus()
    {
        $user = Auth::user();
        
        if (!$user || !$user->researcher) {
            return response()->json([
                'error' => 'User is not registered as a researcher.'
            ], 403);
        }

        $statusData = SubmissionRateLimitService::canSubmit($user->researcher);
        
        return response()->json($statusData);
    }

    /**
     * Get detailed submission attempt history
     * 
     * GET /api/submission-history
     * 
     * Returns array of recent submission attempts (last 24 hours)
     */
    public function getHistory()
    {
        $user = Auth::user();
        
        if (!$user || !$user->researcher) {
            return response()->json([
                'error' => 'User is not registered as a researcher.'
            ], 403);
        }

        $attempts = SubmissionRateLimitService::getRecentAttempts($user->researcher);
        
        return response()->json([
            'recent_attempts' => $attempts,
            'total_24h' => count($attempts),
        ]);
    }
}
