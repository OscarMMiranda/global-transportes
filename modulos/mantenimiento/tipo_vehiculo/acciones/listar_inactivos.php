<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/acciones/listar_inactivos.php
// Devuelve la lista de tipos de vehículo inactivos en formato JSON para DataTables

header('Content-Type: application/json');

// RUTAS CORRECTAS (4 niveles)
require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/permisos.php';

requirePermiso("tipo_vehiculo", "ver");

$conn = getConnection();
if (!$conn) {
    echo json_encode([
        "draw" => 0,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
    exit;
}

// PARÁMETROS DATATABLES
$draw   = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start  = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;

$search = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : "";

$orderColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 1;
$orderDir = (isset($_POST['order'][0]['dir']) && $_POST['order'][0]['dir'] === "desc") ? "DESC" : "ASC";

$columnas = [
    0 => "id",
    1 => "nombre",
    2 => "descripcion",
    3 => "eliminado"
];

$orderColumn = isset($columnas[$orderColumnIndex]) ? $columnas[$orderColumnIndex] : "nombre";

// TOTAL
$sqlTotal = "SELECT COUNT(*) FROM tipo_vehiculo WHERE eliminado = 1";
$totalResult = $conn->query($sqlTotal);
$totalData = $totalResult->fetch_row()[0];

// CONSULTA PRINCIPAL
$sql = "
    SELECT id, nombre, descripcion, eliminado
    FROM tipo_vehiculo
    WHERE eliminado = 1
";

if ($search !== "") {
    $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
}

$sql .= " ORDER BY $orderColumn $orderDir LIMIT $start, $length";

$stmt = $conn->prepare($sql);

if ($search !== "") {
    $like = "%".$search."%";
    $stmt->bind_param("ss", $like, $like);
}

$stmt->execute();
$stmt->store_result();

$stmt->bind_result($id, $nombre, $descripcion, $eliminado);

$data = [];
$contador = $start + 1;

while ($stmt->fetch()) {

    // BADGE DE ESTADO
    $estadoBadge = '<span class="badge bg-danger">Inactivo</span>';

    // ACCIONES PROFESIONALES
    $acciones = '<div class="btn-group" role="group">';

    // Ver detalles
    $acciones .= '
        <button class="btn btn-sm btn-primary btn-ver" title="Ver detalles" data-id="'.$id.'">
            <i class="fa-solid fa-eye"></i>
        </button>
    ';

    // Restaurar
    $acciones .= '
        <button class="btn btn-sm btn-success btn-reactivar" title="Restaurar" data-id="'.$id.'">
            <i class="fa-solid fa-rotate-left"></i>
        </button>
    ';

    // Historial
    $acciones .= '
        <button class="btn btn-sm btn-secondary btn-historial" title="Historial" data-id="'.$id.'">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </button>
    ';

    // Eliminar definitivo (solo si tiene permiso)
    if (tienePermiso("tipo_vehiculo", "hard_delete")) {
        $acciones .= '
            <button class="btn btn-sm btn-danger btn-eliminar-def" title="Eliminar definitivamente" data-id="'.$id.'">
                <i class="fa-solid fa-trash"></i>
            </button>
        ';
    }

    $acciones .= '</div>';

    // ARMAR FILA
    $data[] = [
        "num"         => $contador++,
        "nombre"      => $nombre,
        "descripcion" => $descripcion,
        "estado"      => $estadoBadge,
        "acciones"    => $acciones
    ];
}

// RESPUESTA JSON
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalData,
    "data"            => $data
]);
exit;