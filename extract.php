<?php
$z = new ZipArchive();
if ($z->open(__DIR__ . '/storage/app/templates/report_template.docx')) {
    file_put_contents(__DIR__ . '/document.xml', $z->getFromName('word/document.xml'));
    echo 'Extracted';
}
