<?php
require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['historia'])) {
    $historiaClinica = $_POST['historia'];

    try {
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'es');
        
        $content = '<style>body { font-family: Arial, sans-serif; }</style>';
        $content .= '<h1>Historia Clínica</h1>';
        $content .= '<p>'.nl2br(htmlspecialchars($historiaClinica)).'</p>';

        $html2pdf->writeHTML($content);
        
        $html2pdf->output('historia_clinica.pdf', 'D');
        exit;
    } catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
        $formatter = new \Spipu\Html2Pdf\Exception\ExceptionFormatter($e);
        echo $formatter->getHtmlMessage();
    }
}