<?php
// ============================================================
// CONTROLADOR: GetUbicacionesController.php
// RESPONSABILIDAD: Unificar ENTIDADES + LOCALES + DESTINOS
// ARQUITECTURA LIMPIA — PHP 5.6 COMPATIBLE
// ============================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   VALIDAR CONEXIÓN
   ============================================================ */
if (!$conn) {
    echo json_encode(["ok" => false, "msg" => "Error de conexión"]);
    exit;
}

/* ============================================================
   SQL UNIFICADO
   ============================================================ */
$sql = "
(
    SELECT 
        e.id,
        e.nombre,
        'entidad' AS tipo,
        e.direccion
    FROM entidades e
    WHERE e.estado = 'activo'
)

UNION ALL

(
    SELECT
        l.id,
        l.nombre,
        'local' AS tipo,
        l.direccion
    FROM locales l
)

UNION ALL

(
    SELECT
        d.id,
        d.nombre,
        'destino' AS tipo,
        d.direccion
    FROM destinos d
    WHERE d.estado = 'activo'
)

ORDER BY nombre ASC
";

/* ============================================================
   EJECUTAR CONSULTA
   ============================================================ */
$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "ok"  => false,
        "msg" => "Error SQL: " . $conn->error
    ]);
    exit;
}

/* ============================================================
   ARMAR RESPUESTA
   ============================================================ */
$data = array();

while ($row = $res->fetch_assoc()) {
    $data[] = array(
        "id"        => intval($row["id"]),
        "nombre"    => $row["nombre"],
        "tipo"      => $row["tipo"],
        "direccion" => $row["direccion"]
    );
}

/* ============================================================
   RESPUESTA FINAL
   ============================================================ */
echo json_encode([
    "ok"   => true,
    "data" => $data
]);
exit;
