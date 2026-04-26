<?php
require 'vendor/autoload.php';

$file = __DIR__ . '/storage/app/templates/report_template.docx';
$zip = new ZipArchive;
if ($zip->open($file) === TRUE) {
    $xml = $zip->getFromName('word/document.xml');
    
    // The problem is that <w:t> tags split the text.
    // e.g. <w:t>${</w:t> ... <w:t>code</w:t>
    
    // Easiest fix: Find every instance of `<w:tr>...</w:tr>` and evaluate if it's the data row.
    // The data row contains "code", "title", "researcher", etc.
    
    // Let's replace the whole data row with a very clean, manually constructed row.
    // NO, that loses all their table cell formatting (colors, borders, alignments).
    
    // Instead, let's just strip out proofErr inside w:p, and consolidate all <w:t> values inside of each cell.
    // This is hard to do safely.

    // A common nuclear option for fixing placeholder splitting: 
    // Just regex strip ALL xml tags inside the { and } and the $.
    
    // Let's match `<w:t>${</w:t><w:t>code</w:t><w:t>}</w:t>`
    // The text between $ and } is separated by XML.
    
    // The user typed ${code} ${title} etc.
    $variables = ['code', 'title', 'researcher', 'funding', 'research_type', 'date_received', 'review_type', 'date_first_meeting', 'primary_reviewer', 'decision', 'date_first_decision', 'status'];

    foreach ($variables as $var) {
        $cleanTag = '${' . $var . '}';
        // In XML, this string might look like "$</w:t><w:rPr><w:b/></w:rPr><w:t>{c</w:t><w:t>ode}"
        // Let's just do a brute-force approach. For each variable, we find its fragments.
        // It's too complex.
    }
    
    // Simplest approach: Remove rsid tags, remove proofErr tags.
    $xml = preg_replace('/<w:proofErr[^>]*\/>/', '', $xml);
    $xml = preg_replace('/ w:rsidR="[^"]*"/', '', $xml);
    $xml = preg_replace('/ w:rsidRPr="[^"]*"/', '', $xml);
    $xml = preg_replace('/ w:rsidSect="[^"]*"/', '', $xml);
    $xml = preg_replace('/ w:rsidTr="[^"]*"/', '', $xml);
    $xml = preg_replace('/ w:rsidP="[^"]*"/', '', $xml);

    // Let's look for $ followed by anything up to }
    // Replace all inner tags.
    $xml = preg_replace_callback('/(\$)(.*?)(\})/', function($matches) {
        // Only strip if it looks like a variable
        $inner = strip_tags($matches[2]);
        if (preg_match('/^[a-zA-Z0-9_\{]+$/', $inner)) {
            // Re-wrap in a single w:t tag
            // Wait, we matched starting from $. So $ is in a w:t tag, } is in a w:t tag.
            // We can't just strip XML tags safely because it'll break the structure.
            // But if we return it as pure text, it breaks the enclosing <w:r>
            return $matches[1] . $matches[2] . $matches[3];
        }
        return $matches[0];
    }, $xml);
    
    // Actually, PhpWord's TemplateProcessor has this exact regex for finding variables: /\$\{([^\}]+)\}/
    // But if it's broken, we can't find it.
    
    // Let's just generate a new template!
}
