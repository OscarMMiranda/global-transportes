<?php
// ======================================================
// API: ot_editar_api.php
// MÓDULO: Ordenes de Trabajo
// RESPONSABILIDAD: Obtener datos para edición
// PHP 5.6 Compatible
// ======================================================

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// require_once __DIR__ . '/../../../includes/config.php';

require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

if (!$conn) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'Error de conexión'
    ));

    exit;
}


// ======================================================
// VALIDAR ID
// ======================================================

$id = isset($_POST['id'])
    ? intval($_POST['id'])
    : 0;

if ($id <= 0) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'ID inválido'
    ));

    exit;
}


// ======================================================
// OBTENER OT
// ======================================================

$sql = "
    SELECT *
    FROM ordenes_trabajo
    WHERE id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => $conn->error
    ));

    exit;
}

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$res = $stmt->get_result();

if (
    !$res ||
    $res->num_rows == 0
) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'OT no encontrada'
    ));

    exit;
}

$data = $res->fetch_assoc();


// ======================================================
// CLIENTES
// ======================================================

$clientes = array();

$sqlClientes = "
    SELECT
        id,
        nombre
    FROM clientes
    ORDER BY nombre
";

$rs = $conn->query($sqlClientes);

while (
    $rs &&
    $row = $rs->fetch_assoc()
) {

    $clientes[] = array(
        'id'     => intval($row['id']),
        'nombre' => $row['nombre']
    );
}


// ======================================================
// EMPRESAS
// ======================================================

$empresas = array();

$sqlEmpresas = "
    SELECT
        id,
        razon_social AS nombre
    FROM empresa
    ORDER BY razon_social
";

$rs = $conn->query($sqlEmpresas);

while (
    $rs &&
    $row = $rs->fetch_assoc()
) {

    $empresas[] = array(
        'id'     => intval($row['id']),
        'nombre' => $row['nombre']
    );
}


// ======================================================
// TIPOS OT
// ======================================================

$tipos_ot = array();

$sqlTipos = "
    SELECT
        id,
        nombre
    FROM tipo_ot
    ORDER BY nombre
";

$rs = $conn->query($sqlTipos);

while (
    $rs &&
    $row = $rs->fetch_assoc()
) {

    $tipos_ot[] = array(
        'id'     => intval($row['id']),
        'nombre' => $row['nombre']
    );
}


// ======================================================
// SEMANA CORPORATIVA
// ======================================================

if (
    isset($data['semana_ot']) &&
    !empty($data['semana_ot'])
) {

    $data['semana_ot'] = str_replace(
        'W',
        'S',
        $data['semana_ot']
    );
}


// ======================================================
// RESPUESTA FINAL
// ======================================================

echo json_encode(array(

    'ok'       => true,

    'data'     => $data,

    'clientes' => $clientes,

    'empresas' => $empresas,

    'tipos_ot' => $tipos_ot

));

exit;