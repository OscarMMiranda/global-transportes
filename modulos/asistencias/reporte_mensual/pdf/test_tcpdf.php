<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/tcpdf/tcpdf.php';

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->Write(0, 'TCPDF FUNCIONA');
$pdf->Output('prueba.pdf', 'I');
