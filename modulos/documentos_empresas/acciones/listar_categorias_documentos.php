<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

$conn = getConnection();

$entidad_tipo = isset($_GET['entidad_tipo']) ? trim($_GET['entidad_tipo']) : null;

if ($entidad_tipo !== 'empresa') {
    echo json_encode([]);
    exit;
}

$sql = $conn->prepare("
    SELECT DISTINCT 
        c.id,
        c.nombre
    FROM documentos_empresas_categorias c
    INNER JOIN tipos_documento td 
        ON td.categoria_empresa_id = c.id
    INNER JOIN documentos_config dc 
        ON dc.tipo_documento_id = td.id
    WHERE c.estado = 1
      AND dc.entidad_tipo = 'empresa'
    ORDER BY c.orden ASC, c.nombre ASC
");

if (!$sql) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$sql->execute();
$sql->bind_result($id, $nombre);

$categorias = [];

while ($sql->fetch()) {
    $categorias[] = [
        "id"     => $id,
        "nombre" => $nombre
    ];
}

echo json_encode($categorias);
exit;
