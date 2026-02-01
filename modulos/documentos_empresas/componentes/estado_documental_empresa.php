<?php
//	 archivo: /modulos/documentos_empresas/componentes/estado_documental_empresa.php
function estadoDocumentalEmpresa($conn, $empresa_id)
{
    // 1) Obtener documentos obligatorios
    $sqlReq = "
        SELECT tipo_documento_id
        FROM documentos_config
        WHERE entidad_tipo='empresa'
          AND obligatorio = 1
    ";
    $req = $conn->query($sqlReq);

    $obligatorios = [];
    while ($r = $req->fetch_assoc()) {
        $obligatorios[] = intval($r['tipo_documento_id']);
    }

    if (count($obligatorios) == 0) {
        return "<span class='badge bg-danger'>DESHABILITADO</span>";
    }

    // 2) Obtener documentos cargados (solo actuales)
    $sqlDocs = $conn->prepare("
        SELECT tipo_documento_id, fecha_vencimiento
        FROM documentos
        WHERE entidad_tipo='empresa'
          AND entidad_id=?
          AND is_current=1
          AND eliminado=0
    ");

    if (!$sqlDocs) {
        die("SQL ERROR estadoDocumentalEmpresa: " . $conn->error);
    }

    $sqlDocs->bind_param("i", $empresa_id);
    $sqlDocs->execute();
    $docs = $sqlDocs->get_result();

    $cargados = [];
    while ($d = $docs->fetch_assoc()) {
        $cargados[$d['tipo_documento_id']] = $d;
    }

    // 3) Validación
    foreach ($obligatorios as $tipoDoc) {

        // Falta documento obligatorio
        if (!isset($cargados[$tipoDoc])) {
            return "<span class='badge bg-danger'>DESHABILITADO</span>";
        }

        $doc = $cargados[$tipoDoc];

        // Documento vencido
        if (!empty($doc['fecha_vencimiento']) && strtotime($doc['fecha_vencimiento']) < time()) {
            return "<span class='badge bg-danger'>DESHABILITADO</span>";
        }
    }

    return "<span class='badge bg-success'>HABILITADO</span>";
}
