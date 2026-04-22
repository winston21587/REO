<?php
$z = new ZipArchive();
if ($z->open(__DIR__ . '/public/Acredation Format/RESEARCH ETHICS COMMITTEE.docx')) {
    file_put_contents(__DIR__ . '/document2.xml', $z->getFromName('word/document.xml'));
    echo 'Extracted';
}
