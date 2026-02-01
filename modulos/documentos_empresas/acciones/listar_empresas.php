<?php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../componentes/estado_documental_empresa.php';

$conn = getConnection();

header('Content-Type: application/json');

// ============================================================
// LISTA DE EMPRESAS ACTIVAS
// ============================================================
$sql = "
    SELECT 
        e.id,
        e.razon_social,
        e.ruc
    FROM empresa e
    ORDER BY e.razon_social ASC
";

$res = $conn->query($sql);

if (!$res) {
    die("SQL ERROR: " . $conn->error);
}

$data = [];

while ($row = $res->fetch_assoc()) {

    $empresa_id = intval($row['id']);

    // ============================================================
    // 1) TOTAL DOCUMENTOS OBLIGATORIOS
    // ============================================================
    $sqlReq = "
        SELECT COUNT(*) AS total
        FROM documentos_config
        WHERE entidad_tipo='empresa'
          AND obligatorio = 1
    ";
    $totalObligatorios = $conn->query($sqlReq)->fetch_assoc()['total'];

    // ============================================================
    // 2) DOCUMENTOS CARGADOS
    // ============================================================
    $sqlDocs = $conn->prepare("
        SELECT COUNT(*) 
        FROM documentos 
        WHERE entidad_tipo = 'empresa'
          AND entidad_id = ?
          AND is_current = 1
          AND eliminado = 0
    ");
    $sqlDocs->bind_param("i", $empresa_id);
    $sqlDocs->execute();
    $sqlDocs->bind_result($docsCargados);
    $sqlDocs->fetch();
    $sqlDocs->close();

    // ============================================================
    // 3) DOCUMENTOS POR VENCER
    // ============================================================
    $sqlVencer = $conn->prepare("
        SELECT COUNT(*) 
        FROM documentos 
        WHERE entidad_tipo = 'empresa'
          AND entidad_id = ?
          AND is_current = 1
          AND eliminado = 0
          AND fecha_vencimiento IS NOT NULL
          AND fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 10 DAY)
    ");
    $sqlVencer->bind_param("i", $empresa_id);
    $sqlVencer->execute();
    $sqlVencer->bind_result($porVencer);
    $sqlVencer->fetch();
    $sqlVencer->close();

    // ============================================================
    // 4) PROGRESO
    // ============================================================
    $progreso = $docsCargados . "/" . $totalObligatorios;

    // ============================================================
    // 5) ESTADO DOCUMENTAL
    // ============================================================
    $estado = estadoDocumentalEmpresa($conn, $empresa_id);

    // ============================================================
    // 6) ACCIONES
    // ============================================================
    $acciones = '
        <a href="/modulos/documentos_empresas/vistas/documentos.php?id='.$empresa_id.'" 
           class="btn btn-primary btn-sm">
           Ver documentos
        </a>
    ';

    // ============================================================
    // 7) AGREGAR FILA
    // ============================================================
    $data[] = [
        "razon_social" => $row['razon_social'],
        "ruc" => $row['ruc'],
        "total_documentos" => $progreso,
        "por_vencer" => $porVencer,
        "estado" => $estado,
        "acciones" => $acciones
    ];
}

echo json_encode(["data" => $data]);
exit;
