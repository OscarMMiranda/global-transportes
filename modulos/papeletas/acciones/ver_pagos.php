<?php
// archivo: modulos/papeletas/acciones/ver_pagos.php
// Versión robusta: evita salida accidental, fuerza UTF-8 y devuelve JSON limpio.

ob_start();
header('Content-Type: application/json; charset=utf-8');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// Evitar que warnings rompan el JSON en producción; en desarrollo puedes activar display_errors
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    // limpiar buffer y devolver JSON
    ob_end_clean();
    echo json_encode(['success' => false, 'html' => '<div class="text-danger text-center py-3">ID inválido</div>']);
    exit;
}

$sql = "
    SELECT id, fecha, monto, metodo, referencia, observacion, creado_en
    FROM papeleta_pagos
    WHERE papeleta_id = $id
    ORDER BY fecha DESC, id DESC
";
$res = mysqli_query($conn, $sql);

if (!$res) {
    $err = mysqli_error($conn);
    ob_end_clean();
    echo json_encode(['success' => false, 'html' => '<div class="text-danger text-center py-3">Error en la consulta</div>', 'debug' => $err]);
    exit;
}

$html = '<div class="list-group">';
if (mysqli_num_rows($res) > 0) {
    while ($r = mysqli_fetch_assoc($res)) {
        $fecha = htmlspecialchars($r['fecha'], ENT_QUOTES, 'UTF-8');
        $monto = number_format(floatval($r['monto']), 2, '.', '');
        $metodo = htmlspecialchars($r['metodo'], ENT_QUOTES, 'UTF-8');
        $referencia = htmlspecialchars($r['referencia'], ENT_QUOTES, 'UTF-8');
        $observacion = trim($r['observacion']);
        $observacion_html = $observacion !== '' ? '<div class="mt-1 small text-muted">Obs: ' . htmlspecialchars($observacion, ENT_QUOTES, 'UTF-8') . '</div>' : '';
        $creado_en = isset($r['creado_en']) ? htmlspecialchars($r['creado_en'], ENT_QUOTES, 'UTF-8') : '';

        $html .= '<div class="list-group-item">';
        $html .= '<div class="d-flex w-100 justify-content-between align-items-center">';
        $html .= '<div class="small text-muted">' . $fecha . '</div>';
        $html .= '<div class="fw-semibold">S/ ' . $monto . '</div>';
        $html .= '</div>';
        $html .= '<div class="d-flex w-100 justify-content-between mt-1">';
        $html .= '<div class="small text-muted">Método: ' . ($metodo ?: '—') . '</div>';
        $html .= '<div class="small text-muted">Ref: ' . ($referencia ?: '—') . '</div>';
        $html .= '</div>';
        if ($observacion_html) $html .= $observacion_html;
        if ($creado_en) $html .= '<div class="mt-1 small text-muted">Registrado: ' . $creado_en . '</div>';
        $html .= '</div>';
    }
} else {
    $html .= '<div class="list-group-item text-center text-muted">No hay pagos registrados</div>';
}
$html .= '</div>';

// limpiar buffer y devolver JSON limpio
ob_end_clean();
echo json_encode(['success' => true, 'html' => $html]);
exit;
?>
