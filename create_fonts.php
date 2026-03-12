<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/setasign/fpdf/makefont/makefont.php';

$fontsDir = __DIR__ . '/public/fonts/';
$outDir = __DIR__ . '/storage/app/public/fonts/'; // Store generated fonts here

if (!file_exists($outDir)) {
    mkdir($outDir, 0775, true);
}

// Convert them
try {
    MakeFont($fontsDir . 'Colette.ttf', 'cp1252');
    echo "Converted Colette.ttf\n";
} catch (Exception $e) {
    echo "Failed Colette: " . $e->getMessage() . "\n";
}

try {
    MakeFont($fontsDir . 'Montserrat.ttf', 'cp1252');
    echo "Converted Montserrat.ttf\n";
} catch (Exception $e) {
    echo "Failed Montserrat: " . $e->getMessage() . "\n";
}

try {
    MakeFont($fontsDir . 'Nautilus.otf', 'cp1252');
    echo "Converted Nautilus.otf\n";
} catch (Exception $e) {
    echo "Failed Nautilus: " . $e->getMessage() . "\n";
}

// Move generated files to outDir
foreach (glob($fontsDir . '*.php') as $file) {
    rename($file, $outDir . basename($file));
}
foreach (glob($fontsDir . '*.z') as $file) {
    rename($file, $outDir . basename($file));
}

echo "Done formatting fonts.\n";
