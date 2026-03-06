<?php


require __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

require_once __DIR__ . '/tcpdf/tcpdf.php';

$mes  = intval($_GET['mes'] ?? 0);
$anio = intval($_GET['anio'] ?? 0);

$sql = "SELECT 
            ac.conductor_id,
            ac.fecha,
            ac.tipo_codigo,
            c.nombre_completo AS conductor
        FROM asistencia_conductores ac
        INNER JOIN conductores c ON c.id = ac.conductor_id
        WHERE MONTH(ac.fecha) = $mes
          AND YEAR(ac.fecha) = $anio
        ORDER BY c.nombre_completo ASC, ac.fecha ASC";

$res = $conn->query($sql);

$grupos = [];
while ($r = $res->fetch_assoc()) {
    $cid = $r['conductor_id'];
    $dia = intval(substr($r['fecha'], 8, 2));

    if (!isset($grupos[$cid])) {
        $grupos[$cid] = [
            'nombre' => $r['conductor'],
            'dias'   => []
        ];
    }

    $grupos[$cid]['dias'][$dia] = $r['tipo_codigo'];
}

$diasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

// Crear PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Sistema');
$pdf->SetAuthor('Global Transportes');
$pdf->SetTitle('Matriz de Asistencias');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

$html = '<h2 style="text-align:center;">Matriz de Asistencias - '.$mes.'/'.$anio.'</h2>';
$html .= '<table border="1" cellpadding="3" cellspacing="0"><thead><tr>';
$html .= '<th>Conductor</th>';

for ($d = 1; $d <= $diasMes; $d++) {
    $html .= '<th>'.$d.'</th>';
}

$html .= '</tr></thead><tbody>';

foreach ($grupos as $g) {
    $html .= '<tr><td>'.$g['nombre'].'</td>';
    for ($d = 1; $d <= $diasMes; $d++) {
        $html .= '<td>'.($g['dias'][$d] ?? '').'</td>';
    }
    $html .= '</tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output("matriz_$mes-$anio.pdf", 'I');
