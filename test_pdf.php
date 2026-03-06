<?php
require __DIR__ . "/modulos/asistencias/reporte_mensual/pdf/dompdf/autoload.inc.php";

$html = "<h1>PDF de prueba</h1>";

$dompdf = new Dompdf\Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("prueba.pdf");
