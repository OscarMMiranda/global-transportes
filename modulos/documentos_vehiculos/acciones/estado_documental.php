<?php
// archivo: /modulos/documentos_vehiculos/acciones/estado_documental.php

function estadoDocumentalVehiculo($conn, $vehiculo_id) {

    // Documentos obligatorios (ajusta si cambia la lista)
    $obligatorios = array(1, 2, 3, 4, 5); // SOAT, RC, RTV

    foreach ($obligatorios as $tipo) {

        $sql = "
            SELECT fecha_vencimiento
            FROM documentos
            WHERE entidad_tipo = 'vehiculo'
              AND entidad_id = ?
              AND tipo_documento_id = ?
              AND is_current = 1
              AND eliminado = 0
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $vehiculo_id, $tipo);
        $stmt->execute();
        $stmt->bind_result($fecha_vencimiento);

        if (!$stmt->fetch()) {
            // No existe documento obligatorio
            $stmt->close();
            return false;
        }
        $stmt->close();

        // Documento vencido
        if (!empty($fecha_vencimiento) && $fecha_vencimiento < date('Y-m-d')) {
            return false;
        }
    }

    return true; // Todos los obligatorios existen y están vigentes
}
