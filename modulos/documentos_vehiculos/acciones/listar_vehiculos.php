<?php
// archivo: /modulos/documentos_vehiculos/acciones/listar_vehiculos.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../acciones/estado_documental.php';

$conn = getConnection();

header('Content-Type: application/json');

$sql = 
    "SELECT 
        v.id,
        v.placa,
        v.anio,
        m.nombre AS marca
    FROM vehiculos v
    LEFT JOIN marca_vehiculo m ON m.id = v.marca_id
    WHERE v.activo = 1
    ORDER BY v.placa ASC
";

$res = $conn->query($sql);

$data = [];

while ($row = $res->fetch_assoc()) {

    $vehiculo_id = intval($row['id']);

    // ============================================================
    // 1) TOTAL DOCUMENTOS OBLIGATORIOS (documentos_config)
    // ============================================================
    $sqlReq = "
        SELECT COUNT(*) AS total
        FROM documentos_config
        WHERE entidad_tipo='vehiculo'
          AND obligatorio = 1
    ";
    $totalObligatorios = $conn->query($sqlReq)->fetch_assoc()['total'];

    // ============================================================
    // 2) DOCUMENTOS CARGADOS (solo actuales)
    // ============================================================
    $sqlDocs = $conn->prepare("
        SELECT COUNT(*) 
        FROM documentos 
        WHERE entidad_tipo = 'vehiculo'
          AND entidad_id = ?
          AND is_current = 1
          AND eliminado = 0
    ");
    $sqlDocs->bind_param("i", $vehiculo_id);
    $sqlDocs->execute();
    $sqlDocs->bind_result($docsCargados);
    $sqlDocs->fetch();
    $sqlDocs->close();

    // ============================================================
    // 3) DOCUMENTOS POR VENCER (≤ 10 días)
    // ============================================================
    $sqlVencer = $conn->prepare("
        SELECT COUNT(*) 
        FROM documentos 
        WHERE entidad_tipo = 'vehiculo'
          AND entidad_id = ?
          AND is_current = 1
          AND eliminado = 0
          AND fecha_vencimiento IS NOT NULL
          AND fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 10 DAY)
    ");
    $sqlVencer->bind_param("i", $vehiculo_id);
    $sqlVencer->execute();
    $sqlVencer->bind_result($porVencer);
    $sqlVencer->fetch();
    $sqlVencer->close();

    // ============================================================
    // 4) X/Y
    // ============================================================
    $progreso = $docsCargados . "/" . $totalObligatorios;

    // ============================================================
    // 5) ESTADO DOCUMENTAL
    // ============================================================
    $docOK = estadoDocumentalVehiculo($conn, $vehiculo_id);

    $estado = $docOK
        ? "<span class='badge bg-success'>HABILITADO</span>"
        : "<span class='badge bg-danger'>DESHABILITADO</span>";

    // ============================================================
    // 6) ACCIONES
    // ============================================================
    $acciones = '
        <a href="/modulos/documentos_vehiculos/vistas/documentos.php?id='.$vehiculo_id.'" 
           class="btn btn-primary btn-sm">
           Ver documentos
        </a>
    ';

    // ============================================================
    // 7) ARMAR FILA PARA DATATABLES
    // ============================================================
    $data[] = [
        "placa" => $row['placa'],
        "marca" => $row['marca'],
        "anio" => $row['anio'],
        "total_documentos" => $progreso,
        "por_vencer" => $porVencer,   // ← YA FUNCIONA
        "estado" => $estado,
        "acciones" => $acciones
    ];
}

echo json_encode(["data" => $data]);
exit;
