<?php
$zip = new ZipArchive();
$file = __DIR__ . '/public/Acredation Format/RESEARCH ETHICS COMMITTEE.docx';

if ($zip->open($file) === TRUE) {
    $xmlContent = $zip->getFromName('word/document.xml');
    
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = false;
    libxml_use_internal_errors(true);
    $dom->loadXML($xmlContent);
    libxml_clear_errors();
    
    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
    
    $rows = $xpath->query('//w:tr');
    
    $found = false;
    foreach ($rows as $row) {
        $text = $row->nodeValue; 
        if (strpos($text, 'code') !== false || strpos($text, 'title') !== false) {
            echo "Found row in PUBLIC folder docx: " . $text . "\n";
            $found = true;
            break;
        }
    }
    if (!$found) echo "Not found in PUBLIC folder docx either.\n";
} else {
    echo "Could not open ZipArchive.\n";
}
