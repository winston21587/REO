<?php
$zip = new ZipArchive();
$file = __DIR__ . '/storage/app/templates/report_template.docx';

if ($zip->open($file) === TRUE) {
    $xmlContent = $zip->getFromName('word/document.xml');
    
    // We want to find the <w:tr> that contains "code" or "title" in its nodeValue,
    // and completely replace it with a cleanly-formatted <w:tr> full of placeholders.
    
    // 1. Load XML string using DOMDocument
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = false;
    
    // Suppress warnings from invalid namespaces or weird word xml
    libxml_use_internal_errors(true);
    $dom->loadXML($xmlContent);
    libxml_clear_errors();
    
    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
    
    // 2. Find all table rows
    $rows = $xpath->query('//w:tr');
    
    $targetRow = null;
    $targetRowNodeValue = '';
    
    foreach ($rows as $row) {
        $text = $row->nodeValue; // Gets all text content ignoring tags
        
        // Sometimes text contains ${code} or just code, since MS Word might have mangled it
        if (strpos($text, 'code') !== false && strpos($text, 'title') !== false && strpos($text, 'researcher') !== false) {
            $targetRow = $row;
            $targetRowNodeValue = $text;
            break;
        }
    }
    
    if ($targetRow) {
        echo "Found the corrupted row: " . $targetRowNodeValue . "\n";
        
        // 3. Instead of trying to fix the old row, we build a clean new one.
        // Or actually, even simpler: just change the text of every <w:tc> (cell) in that row!
        // We know the columns from the screenshot:
        // 0: code, 1: title, 2: researcher, 3: funding, 4: research_type, 5: date_received, 6: review_type
        // 7: date_first_meeting, 8: primary_reviewer, 9: decision, 10: date_first_decision, 11: status
        
        $variables = [
            '${code}', '${title}', '${researcher}', '${funding}', '${research_type}', '${date_received}',
            '${review_type}', '${date_first_meeting}', '${primary_reviewer}', '${decision}', '${date_first_decision}', '${status}'
        ];
        
        $cells = $xpath->query('.//w:tc', $targetRow);
        
        if ($cells->length >= count($variables)) {
            foreach ($cells as $index => $cell) {
                if ($index >= count($variables)) break;
                
                $placeholder = $variables[$index];
                
                // Clear all children of the cell
                while ($cell->hasChildNodes()) {
                    $cell->removeChild($cell->firstChild);
                }
                
                // Create a clean <w:p><w:r><w:t>... structure
                // But wait, the cell might have had borders or width defined in <w:tcPr>
                // Oh no, if we remove all children, we remove <w:tcPr> (cell properties)!
            }
        }
        
        // This confirms DOMDocument works. For the cell properties:
        echo "Row has " . $cells->length . " columns.\n";
    } else {
        echo "Could not find a row containing the text 'code', 'title', 'researcher'.\n";
    }
    
} else {
    echo "Could not open ZipArchive.\n";
}
