<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
// CONSULTA PRINCIPAL
// ============================================================
$sql = $conn->prepare("
    SELECT 
        td.id AS tipo_documento_id,
        td.nombre AS tipo_documento,
        dc.obligatorio,
        d.id AS documento_id,
        d.numero,
        d.fecha_inicio,
        d.fecha_vencimiento,
        d.archivo,
        d.version,
        d.tipo_documento_id AS tipo_doc_id_real
    FROM documentos_config dc
    INNER JOIN tipos_documento td 
        ON td.id = dc.tipo_documento_id
    LEFT JOIN documentos d 
        ON d.tipo_documento_id = td.id
        AND d.entidad_tipo = 'empresa'
        AND d.entidad_id = ?
        AND d.is_current = 1
        AND d.eliminado = 0
    WHERE dc.entidad_tipo = 'empresa'
      AND td.categoria_empresa_id = ?
    ORDER BY td.nombre ASC
");

$sql->bind_param("ii", $empresa_id, $categoria_id);
$sql->execute();

$sql->bind_result(
    $tipo_documento_id,
    $tipo_documento,
    $obligatorio,
    $documento_id,
    $numero,
    $fecha_inicio,
    $fecha_vencimiento,
    $archivo,
    $version,
    $tipo_doc_id_real
);

$data = [];

while ($sql->fetch()) {

    // ============================================================
    // DIAS RESTANTES
    // ============================================================
    $dias_restantes = "";
    if (!empty($fecha_vencimiento)) {
        $hoy  = new DateTime();
        $venc = new DateTime($fecha_vencimiento);
        $diff = $hoy->diff($venc);
        $dias_restantes = $diff->format("%r%a");
    }

    // ============================================================
    // ESTADO VISUAL
    // ============================================================
    if (empty($documento_id)) {
        $estado_html = "<span class='badge bg-secondary'>Pendiente</span>";
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
    $url_archivo = "";
    if (!empty($archivo)) {
        $url_archivo = "/uploads/documentos/empresas/" . $archivo;
    }

    // ============================================================
    // ACCIONES
    // ============================================================
    $acciones = "";

    if (empty($documento_id)) {

        $acciones .= '
            <button class="btn btn-sm btn-success btn-subir"
                data-tipo="'.$tipo_documento_id.'"
                data-nombre="'.$tipo_documento.'">
                Adjuntar
            </button>
        ';

    } else {

        $acciones .= '
            <button class="btn btn-sm btn-primary btn-preview" data-url="'.$url_archivo.'">
                Ver
            </button>

            <a class="btn btn-sm btn-info" href="'.$url_archivo.'" download>
                Descargar
            </a>

            <button class="btn btn-sm btn-info btn-historial"
                data-empresa="'.$empresa_id.'"
                data-tipo="'.$tipo_doc_id_real.'">
                Historial
            </button>

            <button class="btn btn-sm btn-warning btn-subir"
                data-tipo="'.$tipo_documento_id.'"
                data-nombre="'.$tipo_documento.'">
                Reemplazar
            </button>
        ';
    }

    $data[] = [
        "tipo_documento"    => $tipo_documento,
        "numero"            => $numero ?: "",
        "fecha_inicio"      => $fecha_inicio ?: "",
        "fecha_vencimiento" => $fecha_vencimiento ?: "",
        "dias_restantes"    => $dias_restantes,
        "estado_html"       => $estado_html,
        "acciones"          => $acciones
    ];
}

echo json_encode(["data" => $data]);
exit;
