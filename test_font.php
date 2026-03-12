<?php
require 'vendor/autoload.php';

$fontPath = __DIR__ . '/public/fonts';

$colette = \TCPDF_FONTS::addTTFfont($fontPath . '/Colette.ttf', 'TrueTypeUnicode', '', 96);
$montserrat = \TCPDF_FONTS::addTTFfont($fontPath . '/Montserrat.ttf', 'TrueTypeUnicode', '', 96);
$nautilus = \TCPDF_FONTS::addTTFfont($fontPath . '/Nautilus.ttf', 'TrueTypeUnicode', '', 96);

var_dump(compact('colette', 'montserrat', 'nautilus'));

$pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
$pdf->AddPage();
if ($colette) $pdf->SetFont($colette, '', 12);
$pdf->Write(0, "Colette Test");

if ($montserrat) $pdf->SetFont($montserrat, '', 12);
$pdf->Write(0, "Montserrat Test");

if ($nautilus) $pdf->SetFont($nautilus, '', 12);
$pdf->Write(0, "Nautilus Test");

if (!file_exists(__DIR__ . '/public/test_pdf.pdf')) {
    $pdf->Output(__DIR__ . '/public/test_pdf.pdf', 'F');
}
echo "\nPDF Generated";
