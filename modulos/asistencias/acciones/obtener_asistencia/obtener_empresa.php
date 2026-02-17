<?php
    // archivo: modulos/asistencias/acciones/obtener_asistencia/obtener_empresa.php
    // Función para obtener el nombre de la empresa por su ID

    
function anexar_empresa($conn, $data) {

    $empresa = obtener_empresa_por_id($conn, $data['empresa_id']);
    $data['empresa_nombre'] = $empresa ? $empresa['razon_social'] : '';

    return $data;
}
