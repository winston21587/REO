<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$reqs = \App\Models\DocumentRequirement::all(['id', 'name', 'file_type', 'is_required', 'is_multiple']);
foreach ($reqs as $r) {
    echo "ID: {$r->id} | Name: {$r->name} | file_type: '{$r->file_type}' | req: {$r->is_required} | mult: {$r->is_multiple}\n";
}
