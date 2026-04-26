<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Researcher;
use App\Services\SubmissionRateLimitService;

class ResetSubmissionLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submission:reset-limits {researcher_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset submission rate limits for a researcher (admin use only)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $researcherId = $this->argument('researcher_id');

        if (!$researcherId) {
            $this->error('❌ Researcher ID is required.');
            $this->info('Usage: php artisan submission:reset-limits {researcher_id}');
            return 1;
        }

        $researcher = Researcher::find($researcherId);
        if (!$researcher) {
            $this->error("❌ Researcher with ID {$researcherId} not found.");
            return 1;
        }

        $user = $researcher->user;
        $this->info("📋 Resetting limits for: {$user->email}");

        SubmissionRateLimitService::resetLimits($researcher);

        $this->info('✅ Rate limits have been reset!');
        $this->line("Researcher can now submit up to 10 submissions per 24 hours again.");

        return 0;
    }
}
