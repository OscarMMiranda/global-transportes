<?php
// archivo: modulos/papeletas/acciones/log_registrar.php

function registrarLog($papeleta_id, $accion, $detalle = null, $usuario_id = null)
{
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
    $conn = getConnection();

    $usuario_id = $usuario_id ?: $_SESSION['usuario_id'];

    $accion = mysqli_real_escape_string($conn, $accion);
    $detalle = $detalle ? mysqli_real_escape_string($conn, $detalle) : null;

    $sql = "
        INSERT INTO papeleta_log (papeleta_id, accion, detalle, usuario_id)
        VALUES ($papeleta_id, '$accion', " . ($detalle ? "'$detalle'" : "NULL") . ", $usuario_id)
    ";

    mysqli_query($conn, $sql);
}
