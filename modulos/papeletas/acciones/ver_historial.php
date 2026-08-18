<?php
// archivo: modulos/papeletas/acciones/ver_historial.php
header('Content-Type: application/json; charset=utf-8');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// Depuración temporal: registrar errores en el log (quitar en producción)
ini_set('display_errors', 0);
error_reporting(E_ALL);
register_shutdown_function(function(){
    $err = error_get_last();
    if ($err) {
        error_log("[ver_historial.php] SHUTDOWN ERROR: " . print_r($err, true));
    }
});

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
$per_page = isset($_POST['per_page']) ? max(1, intval($_POST['per_page'])) : 10;
$tipo_filter = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';

if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "msg" => "ID inválido",
        "html" => "<div class='text-danger text-center py-4'>ID inválido</div>"
    ]);
    exit;
}

$offset = ($page - 1) * $per_page;

/* Construir WHERE dinámico (simple y seguro) */
$whereClauses = ["h.papeleta_id = ?"];
$params = [$id];
$paramTypes = "i";

if ($tipo_filter !== '') {
    $tipo_filter_l = mb_strtolower($tipo_filter, 'UTF-8');
    if ($tipo_filter_l === 'pago') {
        $whereClauses[] = "(LOWER(h.comentario) LIKE ? OR LOWER(ep.nombre) LIKE ?)";
        $params[] = '%pago%';
        $params[] = '%pago%';
        $paramTypes .= "ss";
    } elseif ($tipo_filter_l === 'descuento') {
        $whereClauses[] = "LOWER(h.comentario) LIKE ?";
        $params[] = '%descuento%';
        $paramTypes .= "s";
    } elseif ($tipo_filter_l === 'archivo') {
        $whereClauses[] = "LOWER(h.comentario) LIKE ?";
        $params[] = '%archivo%';
        $paramTypes .= "s";
    }
}

$whereSql = implode(' AND ', $whereClauses);

/* Contar total (usando bind_result para compatibilidad) */
$countSql = "SELECT COUNT(*) AS cnt FROM papeleta_historial h
             LEFT JOIN papeleta_estado ep ON ep.id = h.estado_id
             WHERE $whereSql";

$stmtCount = mysqli_prepare($conn, $countSql);
if ($stmtCount === false) {
    error_log("[ver_historial.php] COUNT PREPARE ERROR: " . mysqli_error($conn));
    echo json_encode(["success" => false, "msg" => "Error interno (count prepare)", "html" => "<div class='text-danger text-center py-4'>Error interno</div>"]);
    exit;
}

// bind dinámico para count
if (!empty($params)) {
    $bind_names = [];
    $bind_names[] = $paramTypes;
    for ($i = 0; $i < count($params); $i++) {
        $bind_names[] = &$params[$i];
    }
    call_user_func_array([$stmtCount, 'bind_param'], $bind_names);
}

if (!mysqli_stmt_execute($stmtCount)) {
    error_log("[ver_historial.php] COUNT EXECUTE ERROR: " . mysqli_stmt_error($stmtCount));
    echo json_encode(["success" => false, "msg" => "Error SQL (count)", "html" => "<div class='text-danger text-center py-4'>Error SQL</div>"]);
    exit;
}

mysqli_stmt_bind_result($stmtCount, $cnt);
mysqli_stmt_fetch($stmtCount);
$total = intval($cnt);
mysqli_stmt_close($stmtCount);

/* Consulta principal (prepared) */
$sql = "
    SELECT 
        h.id,
        h.fecha,
        h.estado_id,
        ep.nombre AS estado_nombre,
        h.comentario,
        COALESCE(u.usuario, '') AS usuario
    FROM papeleta_historial h
    LEFT JOIN usuarios u ON u.id = h.usuario_id
    LEFT JOIN papeleta_estado ep ON ep.id = h.estado_id
    WHERE $whereSql
    ORDER BY h.fecha DESC
    LIMIT ? OFFSET ?
";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    error_log("[ver_historial.php] PREPARE ERROR: " . mysqli_error($conn));
    echo json_encode(["success" => false, "msg" => "Error interno (prepare)", "html" => "<div class='text-danger text-center py-4'>Error interno</div>"]);
    exit;
}

// Bind params: existing params + per_page + offset
$allParams = $params;
$allParamTypes = $paramTypes . "ii";
$allParams[] = $per_page;
$allParams[] = $offset;

$bind_names = [];
$bind_names[] = $allParamTypes;
for ($i = 0; $i < count($allParams); $i++) {
    $bind_names[] = &$allParams[$i];
}
call_user_func_array([$stmt, 'bind_param'], $bind_names);

if (!mysqli_stmt_execute($stmt)) {
    error_log("[ver_historial.php] EXECUTE ERROR: " . mysqli_stmt_error($stmt));
    echo json_encode(["success" => false, "msg" => "Error SQL (execute)", "html" => "<div class='text-danger text-center py-4'>Error SQL</div>"]);
    exit;
}

// Bind de columnas y fetch (compatible sin mysqlnd)
mysqli_stmt_bind_result($stmt, $r_id, $r_fecha, $r_estado_id, $r_estado_nombre, $r_comentario, $r_usuario);
$items = [];
while (mysqli_stmt_fetch($stmt)) {
    $fecha_fmt = $r_fecha ? substr($r_fecha, 0, 19) : '';
    $comentario = $r_comentario !== null ? $r_comentario : '';
    $estado_nombre = $r_estado_nombre !== null ? $r_estado_nombre : '';
    $usuario = $r_usuario !== '' ? $r_usuario : 'Sistema';

    $lc = mb_strtolower($comentario, 'UTF-8');
    $le = mb_strtolower($estado_nombre, 'UTF-8');
    $tipo = 'edicion';
    if (strpos($lc, 'pago') !== false || strpos($le, 'pag') !== false) $tipo = 'pago';
    elseif (strpos($lc, 'descuento') !== false) $tipo = 'descuento';
    elseif (strpos($lc, 'archivo') !== false) $tipo = 'archivo';

    $items[] = [
        'id' => intval($r_id),
        'fecha_hora' => $fecha_fmt,
        'estado_id' => intval($r_estado_id),
        'estado_nombre' => $estado_nombre,
        'comentario' => $comentario,
        'usuario' => $usuario,
        'tipo' => $tipo,
        'titulo' => $estado_nombre ?: ($tipo === 'pago' ? 'Pago' : 'Actividad'),
        'detalle' => $comentario
    ];
}
mysqli_stmt_close($stmt);

/* Construir HTML (compatibilidad) */
$html = "<div class='table-responsive'><table class='table table-bordered table-striped mb-0'>";
$html .= "<thead><tr><th>Fecha</th><th>Estado</th><th>Comentario</th><th>Usuario</th></tr></thead><tbody>";
if (count($items) === 0) {
    $html .= "<tr><td colspan='4' class='text-center text-muted py-4'>No hay historial registrado</td></tr>";
} else {
    foreach ($items as $it) {
        $f = htmlspecialchars($it['fecha_hora'], ENT_QUOTES, 'UTF-8');
        $est = htmlspecialchars($it['estado_nombre'], ENT_QUOTES, 'UTF-8');
        $com = $it['detalle'] !== '' ? htmlspecialchars($it['detalle'], ENT_QUOTES, 'UTF-8') : "<span class='text-muted'>Sin comentario</span>";
        $usr = $it['usuario'] !== '' ? htmlspecialchars($it['usuario'], ENT_QUOTES, 'UTF-8') : "<span class='text-muted'>Sistema</span>";
        $html .= "<tr><td>{$f}</td><td>{$est}</td><td>{$com}</td><td>{$usr}</td></tr>";
    }
}
$html .= "</tbody></table></div>";

$response = [
    "success" => true,
    "data" => [
        "items" => $items,
        "total" => $total,
        "page" => $page,
        "per_page" => $per_page,
        "last_update" => date('Y-m-d H:i:s')
    ],
    "html" => $html
];

echo json_encode($response);
exit;
?>

