<?php
require 'vendor/autoload.php';
try {
    $t = new \PhpOffice\PhpWord\TemplateProcessor('storage/app/templates/report_template.docx');
    echo implode(", ", $t->getVariables());
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
