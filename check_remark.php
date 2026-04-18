<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = \DB::select('SHOW COLUMNS FROM reviewer_file_remarks');
file_put_contents('schema.json', json_encode($columns, JSON_PRETTY_PRINT));
