<?php
// archivo: /modulos/documentos_empresas/acciones/listar_documentos_empresa.php

require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json');

// ============================================================
// VALIDAR PARAMETROS
// ============================================================
if (!isset($_GET['empresa_id']) || !is_numeric($_GET['empresa_id'])) {
    echo json_encode(["data" => []]);
    exit;
}

if (!isset($_GET['categoria_id']) || !is_numeric($_GET['categoria_id'])) {
    echo json_encode(["data" => []]);
    exit;
}

$empresa_id   = intval($_GET['empresa_id']);
$categoria_id = intval($_GET['categoria_id']);

$conn = getConnection();

// ============================================================
// CONSULTA PRINCIPAL (FILTRADA POR CATEGORÍA)
// ============================================================
$sql = $conn->prepare("
    SELECT 
        d.id,
        d.numero,
        d.fecha_inicio,
        d.fecha_vencimiento,
        d.version,
        d.archivo,
        td.nombre AS tipo_documento
    FROM documentos d
    INNER JOIN tipos_documento td 
        ON td.id = d.tipo_documento_id
    WHERE d.entidad_tipo = 'empresa'
      AND d.entidad_id = ?
      AND d.is_current = 1
      AND d.eliminado = 0
      AND td.categoria_empresa_id = ?
    ORDER BY td.nombre ASC
");

$sql->bind_param("ii", $empresa_id, $categoria_id);
$sql->execute();
$res = $sql->get_result();

$data = [];

while ($row = $res->fetch_assoc()) {

    // ============================================================
    // DIAS RESTANTES
    // ============================================================
    $dias_restantes = "";
    if (!empty($row['fecha_vencimiento'])) {
        $hoy = new DateTime();
        $venc = new DateTime($row['fecha_vencimiento']);
        $diff = $hoy->diff($venc);
        $dias_restantes = $diff->format("%r%a");
    }

    // ============================================================
    // ESTADO VISUAL
    // ============================================================
    if ($dias_restantes === "") {
        $estado_html = "<span class='badge bg-secondary'>Sin fecha</span>";
    } elseif ($dias_restantes < 0) {
        $estado_html = "<span class='badge bg-danger'>Vencido</span>";
    } elseif ($dias_restantes <= 10) {
        $estado_html = "<span class='badge bg-warning text-dark'>Por vencer</span>";
    } else {
        $estado_html = "<span class='badge bg-success'>Vigente</span>";
    }

    // ============================================================
    // URL DEL ARCHIVO
    // ============================================================
    $url_archivo = "/uploads/documentos/" . $row['archivo'];

    // ============================================================
    // ACCIONES
    // ============================================================
    $acciones = '
        <button class="btn btn-sm btn-primary btn-preview" data-url="'.$url_archivo.'">
            Ver
        </button>

        <button class="btn btn-sm btn-dark btn-historial" data-id="'.$row['id'].'">
            Historial
        </button>
    ';

    // ============================================================
    // AGREGAR FILA
    // ============================================================
    $data[] = [
        "tipo_documento"   => $row['tipo_documento'],
        "numero"           => $row['numero'],
        "fecha_inicio"     => $row['fecha_inicio'],
        "fecha_vencimiento"=> $row['fecha_vencimiento'],
        "dias_restantes"   => $dias_restantes,
        "estado_html"      => $estado_html,
        "acciones"         => $acciones
    ];
}

echo json_encode(["data" => $data]);
exit;
