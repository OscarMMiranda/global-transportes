<?php
// archivo : /modulos/mantenimiento/entidades/models/TipoLugarModel.php

function obtenerTiposActivos($conn) {
    $sql = "SELECT id, nombre FROM tipo_lugares WHERE estado = 'activo' ORDER BY nombre ASC";
    $result = mysqli_query($conn, $sql);
    $tipos = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $tipos[] = $row;
    }

    return $tipos;
}
?>