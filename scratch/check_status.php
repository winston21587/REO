<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\DocumentRequirement;
use App\Services\SubmissionRateLimitService;

echo "=== Document Requirements ===\n";
foreach (DocumentRequirement::all() as $req) {
    echo "#{$req->id} | {$req->name} | file_type: '{$req->file_type}' | required: " . ($req->is_required ? 'YES' : 'NO') . "\n";
}

echo "\n=== Researchers & Quota Status ===\n";
$jdom = User::where('email', 'jdom@gmail.com')->first();
if ($jdom) {
    echo "jdom password check:\n";
    foreach (['123456', 'password', '12345678', 'adminpassword'] as $p) {
        echo "  '$p': " . (Illuminate\Support\Facades\Hash::check($p, $jdom->password) ? 'YES' : 'NO') . "\n";
    }
}

foreach ($users as $u) {
    // Reset limits for all researchers to ensure complete fresh quota
    SubmissionRateLimitService::resetLimits($u->researcher);
    $status = SubmissionRateLimitService::getSubmissionStatus($u->researcher);
    $can = SubmissionRateLimitService::canSubmit($u->researcher);
    echo "User ID: {$u->id} | {$u->first_name} {$u->last_name} ({$u->email}) | Researcher ID: {$u->researcher->id}\n";
    echo "  Hourly: {$status['hourly']['current']}/{$status['hourly']['limit']} (Remaining: {$status['hourly']['remaining']})\n";
    echo "  Daily:  {$status['daily']['current']}/{$status['daily']['limit']} (Remaining: {$status['daily']['remaining']})\n";
    echo "  Can Submit: " . ($can['can_submit'] ? 'YES' : 'NO') . "\n";
}
