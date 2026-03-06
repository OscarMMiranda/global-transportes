<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

/* ============================================================
   FUNCIÓN: convertir IDs de roles a nombres
   ============================================================ */
function obtenerNombreRoles($conn, $ids) {
    if (!is_array($ids) || empty($ids)) {
        return [];
    }

    $idsLimpios = array_map('intval', $ids);
    $idsStr = implode(',', $idsLimpios);

    $sql = "SELECT id, nombre FROM roles WHERE id IN ($idsStr)";
    $res = $conn->query($sql);

    $map = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $map[$row['id']] = $row['nombre'];
        }
    }

    $resultado = [];
    foreach ($idsLimpios as $id) {
        $resultado[] = isset($map[$id]) ? $map[$id] : "ID $id";
    }

    return $resultado;
}

/* ============================================================
   CONSULTA DEL HISTORIAL
   ============================================================ */
$sql = "
    SELECT accion, cambios_json, usuario_id, ip, fecha
    FROM historial
    WHERE tabla = 'empleados'
      AND registro_id = $id
    ORDER BY fecha DESC
";

$res = $conn->query($sql);

$html = "<ul class='list-group'>";

if (!$res || $res->num_rows === 0) {

    $html .= "<li class='list-group-item text-muted'>Sin historial registrado.</li>";

} else {

    while ($row = $res->fetch_assoc()) {

        $accion     = htmlspecialchars($row['accion']);
        $usuario_id = htmlspecialchars($row['usuario_id']);
        $ip         = htmlspecialchars($row['ip']);
        $fecha      = htmlspecialchars($row['fecha']);

        $html .= "
            <li class='list-group-item'>
                <strong>Acción:</strong> $accion<br>
                <strong>Usuario ID:</strong> $usuario_id<br>
                <strong>IP:</strong> $ip<br>
                <strong>Fecha:</strong> $fecha<br>
        ";

        $raw = $row['cambios_json'];

        if (!empty($raw) && is_string($raw)) {

            $json = @json_decode($raw, true);

            if (is_array($json)) {

                $html .= "<ul style='margin-top:8px'>";

                foreach ($json as $campo => $detalle) {

                    /* ============================================================
                       ROLES: convertir IDs a nombres
                       ============================================================ */
                    if ($campo === 'roles') {

                        $antesArr = isset($detalle['antes']) ? $detalle['antes'] : [];
                        $despuesArr = isset($detalle['despues']) ? $detalle['despues'] : [];

                        $antesNombres = obtenerNombreRoles($conn, $antesArr);
                        $despuesNombres = obtenerNombreRoles($conn, $despuesArr);

                        $antes = implode(', ', $antesNombres);
                        $despues = implode(', ', $despuesNombres);

                        $html .= "<li><strong>Roles:</strong> $antes → $despues</li>";

                    } else {

                        $antes = isset($detalle['antes']) ? htmlspecialchars($detalle['antes']) : '';
                        $despues = isset($detalle['despues']) ? htmlspecialchars($detalle['despues']) : '';

                        $html .= "<li><strong>$campo:</strong> $antes → $despues</li>";
                    }
                }

                $html .= "</ul>";

            } else {
                $html .= "<div class='text-muted'>Sin cambios registrados.</div>";
            }

        } else {
            $html .= "<div class='text-muted'>Sin cambios registrados.</div>";
        }

        $html .= "</li>";
    }
}

$html .= "</ul>";

echo json_encode([
    "success" => true,
    "html" => $html
]);
