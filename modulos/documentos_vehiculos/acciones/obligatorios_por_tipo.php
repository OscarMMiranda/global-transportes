<?php
    // archivo: /modulos/documentos_vehiculos/acciones/obligatorios_por_tipo.php

function obtenerDocumentosObligatorios($conn, $tipo_id) {

    $sql = "
        SELECT tipo_documento_id
        FROM documentos_obligatorios_tipo
        WHERE tipo_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tipo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $lista = array();
    while ($row = $result->fetch_assoc()) {
        $lista[] = intval($row['tipo_documento_id']);
    }

    $stmt->close();
    return $lista;
}
