<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DocumentRequirement;

$updates = [
    1 => 'PDF, Word',
    2 => 'PDF, Word',
    3 => 'PDF, Word',
    4 => 'PDF, Word',
    5 => 'PDF, Word',
    6 => 'PDF, Word',
    7 => 'PDF, Word',
    8 => 'PDF, Word',
    9 => 'PDF, Word',
    10 => 'PDF, Word, Others',
    11 => 'PDF, Word, Others',
];

foreach ($updates as $id => $fileType) {
    $req = DocumentRequirement::find($id);
    if ($req) {
        $req->file_type = $fileType;
        $req->save();
        echo "Updated Req #{$id} ({$req->name}) to file_type: '{$fileType}'\n";
    }
}

echo "\nVerification of all DocumentRequirements:\n";
foreach (DocumentRequirement::all() as $r) {
    echo "ID: {$r->id} | {$r->name} | file_type: '{$r->file_type}'\n";
}
