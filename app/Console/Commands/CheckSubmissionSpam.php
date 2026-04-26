<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Researcher;
use App\Services\SubmissionRateLimitService;
use Illuminate\Support\Facades\Cache;

class CheckSubmissionSpam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submission:check-spam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for suspicious submission patterns and spam attempts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for suspicious submission patterns...');

        $researchers = Researcher::all();
        $suspiciousResearchers = [];

        foreach ($researchers as $researcher) {
            $attempts = SubmissionRateLimitService::getRecentAttempts($researcher);
            
            if (empty($attempts)) {
                continue;
            }

            // Pattern 1: Multiple failed attempts in short time (brute force attempt)
            $failedAttempts = array_filter($attempts, fn($a) => $a['status'] === 'failed');
            if (count($failedAttempts) >= 5) {
                $suspiciousResearchers[] = [
                    'researcher' => $researcher,
                    'reason' => 'Multiple failed attempts (' . count($failedAttempts) . ')',
                    'severity' => 'medium',
                ];
            }

            // Pattern 2: Rapid successful submissions (likely bot/spam)
            $successfulAttempts = array_filter($attempts, fn($a) => $a['status'] === 'success');
            $recentSuccessful = array_filter(
                $successfulAttempts,
                fn($a) => (now()->timestamp - $a['timestamp']) < 300 // Last 5 minutes
            );
            if (count($recentSuccessful) >= 3) {
                $suspiciousResearchers[] = [
                    'researcher' => $researcher,
                    'reason' => 'Rapid submissions (' . count($recentSuccessful) . ' in 5 min)',
                    'severity' => 'high',
                ];
            }

            // Pattern 3: Currently rate limited but trying again
            $status = SubmissionRateLimitService::getSubmissionStatus($researcher);
            if ($status['hourly']['current'] >= $status['hourly']['limit']) {
                $recentAttemptsAfterLimit = array_filter(
                    $attempts,
                    fn($a) => (now()->timestamp - $a['timestamp']) < 60 // Last minute
                );
                if (count($recentAttemptsAfterLimit) > 0) {
                    $suspiciousResearchers[] = [
                        'researcher' => $researcher,
                        'reason' => 'Attempting after rate limit exceeded',
                        'severity' => 'low',
                    ];
                }
            }
        }

        if (empty($suspiciousResearchers)) {
            $this->info('✅ No suspicious patterns detected.');
            return 0;
        }

        $this->warn('⚠️ Found ' . count($suspiciousResearchers) . ' researchers with suspicious patterns:');
        $this->newLine();

        foreach ($suspiciousResearchers as $suspicious) {
            $researcher = $suspicious['researcher'];
            $user = $researcher->user;
            $severity = $suspicious['severity'];

            $severityEmoji = match($severity) {
                'high' => '🔴',
                'medium' => '🟡',
                'low' => '🟢',
                default => '⚫'
            };

            $this->line("{$severityEmoji} [{$severity}] {$user->email} ({$researcher->id}) - {$suspicious['reason']}");
        }

        $this->newLine();
        $this->info('💡 Tip: Use "submission:reset-limits {researcher_id}" to reset limits for a researcher.');

        return 0;
    }
}
