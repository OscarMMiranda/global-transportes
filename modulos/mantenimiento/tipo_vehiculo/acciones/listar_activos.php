<?php
header('Content-Type: application/json');

// RUTAS CORRECTAS (4 niveles)
require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/permisos.php';

requirePermiso("tipo_vehiculo", "ver");

$conn = getConnection();
if (!$conn) {
    echo json_encode(["draw"=>0,"recordsTotal"=>0,"recordsFiltered"=>0,"data"=>[]]);
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
$sqlTotal = "SELECT COUNT(*) FROM tipo_vehiculo WHERE eliminado = 0";
$totalResult = $conn->query($sqlTotal);
$totalData = $totalResult->fetch_row()[0];

// CONSULTA PRINCIPAL
$sql = "
    SELECT id, nombre, descripcion, eliminado
    FROM tipo_vehiculo
    WHERE eliminado = 0
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

    $estadoBadge = '<span class="badge bg-success">Activo</span>';

    $acciones = '
        <button class="btn btn-info btn-sm btn-ver" data-id="'.$id.'">
            <i class="fa fa-eye"></i>
        </button>

        <button class="btn btn-primary btn-sm btn-editar" data-id="'.$id.'">
            <i class="fa fa-edit"></i>
        </button>

        <button class="btn btn-warning btn-sm btn-desactivar" data-id="'.$id.'">
            <i class="fa fa-ban"></i>
        </button>
    ';

    $data[] = [
        "num"         => $contador++,
        "nombre"      => $nombre,
        "descripcion" => $descripcion,
        "estado"      => $estadoBadge,
        "acciones"    => $acciones
    ];
}

echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalData,
    "data"            => $data
]);
exit;